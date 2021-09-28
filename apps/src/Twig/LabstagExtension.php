<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Service\GuardService;
use Labstag\Service\PhoneService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Registry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends AbstractExtension
{
    public const FOLDER_ENTITY = 'Labstag\\Entity\\';

    public const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    protected AttachmentRepository $attachmentRepository;

    protected CacheManager $cache;

    protected GroupeRepository $groupeRepository;

    protected GuardService $guardService;

    protected LoggerInterface $logger;

    protected PhoneService $phoneService;

    protected TokenStorageInterface $token;

    protected Registry $workflows;

    public function __construct(
        PhoneService $phoneService,
        CacheManager $cache,
        Registry $workflows,
        TokenStorageInterface $token,
        LoggerInterface $logger,
        GroupeRepository $groupeRepository,
        AttachmentRepository $attachmentRepository,
        GuardService $guardService
    )
    {
        $this->cache                = $cache;
        $this->attachmentRepository = $attachmentRepository;
        $this->logger               = $logger;
        $this->guardService         = $guardService;
        $this->groupeRepository     = $groupeRepository;
        $this->workflows            = $workflows;
        $this->token                = $token;
        $this->phoneService         = $phoneService;
    }

    public function classEntity($entity)
    {
        $class = get_class($entity);

        $class = substr($class, strpos($class, self::FOLDER_ENTITY) + strlen(self::FOLDER_ENTITY));

        return trim(strtolower($class));
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

        return $newFile;
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

        return $newFile;
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

    public function getFilters(): array
    {
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            $filters[] = new TwigFilter($key, [$this, $function]);
        }

        return $filters;
    }

    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            $functions[] = new TwigFunction($key, [$this, $function]);
        }

        return $functions;
    }

    public function guardAccessGroupRoutes(Groupe $groupe): bool
    {
        $routes = $this->guardService->getGuardRoutesForGroupe($groupe);

        return (0 != count($routes)) ? true : false;
    }

    public function guardAccessUserRoutes(User $user): bool
    {
        $routes = $this->guardService->getGuardRoutesForUser($user);

        return (0 != count($routes)) ? true : false;
    }

    public function guardRoute(string $route): bool
    {
        $token = $this->token->getToken();

        return $this->guardService->guardRoute($route, $token);
    }

    public function guardRouteEnableGroupe(string $route, Groupe $groupe): bool
    {
        return $this->guardService->guardRouteEnableGroupe($route, $groupe);
    }

    public function guardRouteEnableUser(string $route, User $user): bool
    {
        return $this->guardService->guardRouteEnableUser($route, $user);
    }

    /**
     * Gets the browser path for the image and filter to apply.
     *
     * @param string      $path
     * @param string      $filter
     * @param null|string $resolver
     * @param int         $referenceType
     *
     * @return string
     */
    public function imagefilter(
        $path,
        $filter,
        array $config = [],
        $resolver = null,
        $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    )
    {
        $url = $this->cache->getBrowserPath(
            parse_url($path, PHP_URL_PATH),
            $filter,
            $config,
            $resolver,
            $referenceType
        );

        return parse_url($url, PHP_URL_PATH);
    }

    public function isPhoneValid(string $number, string $country): bool
    {
        $verif = $this->phoneService->verif($number, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }

    public function verifPhone(string $country, string $phone)
    {
        $verif = $this->phoneService->verif($phone, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }

    public function workflowHas($entity)
    {
        return $this->workflows->has($entity);
    }

    protected function setTypeformClass(array $class): string
    {
        if (is_object($class['data'])) {
            $tabClass = explode('\\', get_class($class['data']));

            return end($tabClass);
        }

        return $class['form']->vars['unique_block_prefix'];
    }

    private function getFiltersFunctions()
    {
        return [
            'attachment'               => 'getAttachment',
            'class_entity'             => 'classEntity',
            'formClass'                => 'formClass',
            'formPrototype'            => 'formPrototype',
            'guard_group_access'       => 'guardAccessGroupRoutes',
            'guard_route_enable_group' => 'guardRouteEnableGroupe',
            'guard_route_enable_user'  => 'guardRouteEnableUser',
            'guard_route'              => 'guardRoute',
            'guard_user_access'        => 'guardAccessUserRoutes',
            'imagefilter'              => 'imagefilter',
            'phone_valid'              => 'isPhoneValid',
            'verifPhone'               => 'verifPhone',
            'workflow_has'             => 'workflowHas',
        ];
    }
}
