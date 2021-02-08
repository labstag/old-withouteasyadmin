<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Service\GuardService;
use Labstag\Service\PhoneService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Registry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends AbstractExtension
{

    protected PhoneService $phoneService;

    protected Registry $workflows;

    protected GuardService $guardService;

    protected TokenStorageInterface $token;

    protected GroupeRepository $groupeRepository;

    protected LoggerInterface $logger;

    protected AttachmentRepository $attachmentRepository;

    const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    const FOLDER_ENTITY = 'Labstag\\Entity\\';

    public function __construct(
        PhoneService $phoneService,
        Registry $workflows,
        TokenStorageInterface $token,
        LoggerInterface $logger,
        GroupeRepository $groupeRepository,
        AttachmentRepository $attachmentRepository,
        GuardService $guardService
    )
    {
        $this->attachmentRepository = $attachmentRepository;
        $this->logger               = $logger;
        $this->guardService         = $guardService;
        $this->groupeRepository     = $groupeRepository;
        $this->workflows            = $workflows;
        $this->token                = $token;
        $this->phoneService         = $phoneService;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('workflow_has', [$this, 'workflowHas']),
            new TwigFilter('guard_route', [$this, 'guardRoute']),
            new TwigFilter('class_entity', [$this, 'classEntity']),
            new TwigFilter('attachment', [$this, 'getAttachment']),
            new TwigFilter('phone_valid', [$this, 'isPhoneValid']),
            new TwigFilter('guard_route_enable_group', [$this, 'guardRouteEnableGroupe']),
            new TwigFilter('guard_route_enable_user', [$this, 'guardRouteEnableUser']),
            new TwigFilter('guard_user_access', [$this, 'guardAccessUserRoutes']),
            new TwigFilter('guard_group_access', [$this, 'guardAccessGroupRoutes']),
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
            new TwigFunction('class_entity', [$this, 'classEntity']),
            new TwigFunction('attachment', [$this, 'getAttachment']),
            new TwigFunction('phone_valid', [$this, 'isPhoneValid']),
            new TwigFunction('guard_route_enable_group', [$this, 'guardRouteEnableGroupe']),
            new TwigFunction('guard_route_enable_user', [$this, 'guardRouteEnableUser']),
            new TwigFunction('guard_user_access', [$this, 'guardAccessUserRoutes']),
            new TwigFunction('guard_group_access', [$this, 'guardAccessGroupRoutes']),
            new TwigFunction('formClass', [$this, 'formClass']),
            new TwigFunction('verifPhone', [$this, 'verifPhone']),
            new TwigFunction('formPrototype', [$this, 'formPrototype']),
        ];
    }

    public function isPhoneValid(string $number, string $country): bool
    {
        $verif = $this->phoneService->verif($number, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }

    public function getAttachment($data): ?Attachment
    {
        if (is_null($data)) {
            return null;
        }

        $id         = $data->getId();
        $attachment = $this->attachmentRepository->findOneBy(['id' => $id]);
        if (is_null($attachment)) {
            return null;
        }

        $file = $attachment->getName();
        if (!is_file($file)) {
            return null;
        }

        return $attachment;
    }

    public function guardRouteEnableUser(string $route, User $user): bool
    {
        return $this->guardService->guardRouteEnableUser($route, $user);
    }

    public function classEntity($entity)
    {
        $class = get_class($entity);

        $class = substr($class, strpos($class, self::FOLDER_ENTITY) + strlen(self::FOLDER_ENTITY));

        return trim(strtolower($class));
    }

    public function guardAccessUserRoutes(User $user): bool
    {
        $routes = $this->guardService->getGuardRoutesForUser($user);

        return (0 != count($routes)) ? true : false;
    }

    public function guardAccessGroupRoutes(Groupe $groupe): bool
    {
        $routes = $this->guardService->getGuardRoutesForGroupe($groupe);

        return (0 != count($routes)) ? true : false;
    }

    public function guardRouteEnableGroupe(string $route, Groupe $groupe): bool
    {
        return $this->guardService->guardRouteEnableGroupe($route, $groupe);
    }

    public function guardRoute(string $route): bool
    {
        $token = $this->token->getToken();

        return $this->guardService->guardRoute($route, $token);
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
        if ('collection_entry' != $blockPrefixes[1]) {
            return $file;
        }

        $type = $blockPrefixes[2];

        $newFile = 'prototype/'.$type.'.html.twig';
        if (!is_file(__DIR__.'/../../templates/'.$newFile)) {
            $this->logger->info('Fichier manquant : '.__DIR__.'/../../templates/'.$newFile);

            return $file;
        }

        $file = $newFile;

        return $file;
    }

    protected function setTypeformClass(array $class): string
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
            $this->logger->info('Fichier manquant : '.__DIR__.'/../../templates/'.$newFile);

            return $file;
        }

        $file = $newFile;

        return $file;
    }
}
