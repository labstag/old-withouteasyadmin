<?php

namespace Labstag\Twig;

use Labstag\Entity\Groupe;
use Labstag\Service\GuardRouteService;
use Labstag\Service\PhoneService;
use Symfony\Component\Workflow\Registry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends AbstractExtension
{

    private PhoneService $phoneService;

    protected Registry $workflows;

    protected GuardRouteService $guardRouteService;

    const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    public function __construct(
        PhoneService $phoneService,
        Registry $workflows,
        GuardRouteService $guardRouteService
    )
    {
        $this->guardRouteService = $guardRouteService;
        $this->workflows         = $workflows;
        $this->phoneService      = $phoneService;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('workflow_has', [$this, 'workflowHas']),
            new TwigFilter('guard_route', [$this, 'guardRoute']),
            new TwigFilter('guard_route_enable', [$this, 'guardRouteEnable']),
            new TwigFilter('formClass', [$this, 'formClass']),
            new TwigFilter('verifPhone', [$this, 'verifPhone']),
            new TwigFilter('formPrototype', [$this, 'formPrototype']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('workflow_has', [$this, 'workflowHas']),
            new TwigFunction('guard_route', [$this, 'guardRoute']),
            new TwigFunction('guard_route_enable', [$this, 'guardRouteEnable']),
            new TwigFunction('formClass', [$this, 'formClass']),
            new TwigFunction('verifPhone', [$this, 'verifPhone']),
            new TwigFunction('formPrototype', [$this, 'formPrototype']),
        ];
    }

    public function guardRouteEnable(string $route, Groupe $groupe): bool
    {
        $all = $this->guardRouteService->all();
        if (!array_key_exists($route, $all)) {
            return false;
        }

        $data     = $all[$route];
        $defaults = $data->getDefaults();
        $matches  = [];
        preg_match(self::REGEX_CONTROLLER_ADMIN, $defaults['_controller'], $matches);
        if (0 != count($matches) && 'visiteur' == $groupe->getCode()) {
            return false;
        }

        return true;
    }

    public function guardRoute(string $route): bool
    {
        dump($route, 'fonction non terminé');
        return true;
    }

    public function workflowHas($entity)
    {
        return $this->workflows->has($entity);
    }

    public function verifPhone(string $country, string $phone)
    {
        $verif = $this->phoneService->verif($phone, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }

    public function formPrototype(array $blockPrefixes): string
    {
        $file = '';
        if ($blockPrefixes[1] != 'collection_entry') {
            return $file;
        }

        $type = $blockPrefixes[2];

        $newFile = 'prototype/'.$type.'.html.twig';
        if (!is_file(__DIR__.'/../../templates/'.$newFile)) {
            dump('Fichier manquant : '.__DIR__.'/../../templates/'.$newFile);

            return $file;
        }

        $file = $newFile;

        return $file;
    }

    private function setTypeformClass(array $class): string
    {
        if (is_object($class['data'])) {
            $tabClass = explode('\\', get_class($class['data']));
            $type     = end($tabClass);

            return $type;
        }

        $type = $class['form']->vars['unique_block_prefix'];

        return $type;
    }

    public function formClass($class)
    {
        $file = '';

        $methods = get_class_vars(get_class($class));
        if (!array_key_exists('vars', $methods)) {
            return $file;
        }

        $vars = $class->vars;

        if (!array_key_exists('data', $vars) || is_null($vars['data'])) {
            return $file;
        }

        $type = $this->setTypeformClass($vars);

        $newFile = 'forms/'.$type.'.html.twig';
        if (!is_file(__DIR__.'/../../templates/'.$newFile)) {
            dump('Fichier manquant : '.__DIR__.'/../../templates/'.$newFile);

            return $file;
        }

        $file = $newFile;

        return $file;

    }
}
