<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\AttachmentRepository;
use Labstag\Service\GuardService;
use Labstag\Service\ParagraphService;
use Labstag\Service\PhoneService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Registry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends AbstractExtension
{
    /**
     * @var string
     */
    final public const FOLDER_ENTITY = 'Labstag\\Entity\\';

    /**
     * @var string
     */
    final public const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    public function __construct(
        protected ContainerBagInterface $containerBag,
        protected RouterInterface $router,
        protected PhoneService $phoneService,
        protected CacheManager $cacheManager,
        protected Registry $registry,
        protected TokenStorageInterface $tokenStorage,
        protected LoggerInterface $logger,
        protected GuardService $guardService,
        protected ParagraphService $paragraphService,
        protected AttachmentRepository $attachmentRepository
    )
    {
    }

    public function classEntity($entity): string
    {
        $class = substr(
            (string) $entity::class,
            strpos((string) $entity::class, self::FOLDER_ENTITY) + strlen(self::FOLDER_ENTITY)
        );

        return trim(strtolower($class));
    }

    public function formClass($class)
    {
        $file = 'forms/default.html.twig';

        $methods = get_class_vars($class::class);
        if (!array_key_exists('vars', $methods)
            || !array_key_exists('data', $class->vars)
            || is_null($class->vars['data'])
        ) {
            return $file;
        }

        $vars   = $class->vars;
        $type   = strtolower($this->setTypeformClass($vars));
        $folder = __DIR__.'/../../templates/';
        $files  = $this->setFilesformClass($type, $class);
        $view   = end($files);

        foreach ($files as $file) {
            if (is_file($folder.$file)) {
                $view = $file;

                break;
            }
        }

        $this->dump(['templates-form', $type, $files, $view]);

        return $view;
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

    public function getBlockClass($data): string
    {
        $block = $data->getBlock();

        return 'block-'.$block->getType();
    }

    public function getBlockId($data): string
    {
        $block = $data->getBlock();

        return 'block-'.$block->getType().'-'.$block->getId();
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            $filters[] = new TwigFilter($key, [$this, $function]);
        }

        return $filters;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            $functions[] = new TwigFunction($key, [$this, $function]);
        }

        return $functions;
    }

    public function getParagraphClass($data): string
    {
        $paragraph = $data->getParagraph();
        $dataClass = [
            'paragraph-'.$paragraph->getType(),
        ];

        $code = $paragraph->getBackground();
        if (!empty($code)) {
            $dataClass[] = 'm--background-'.$code;
        }

        $code = $paragraph->getColor();
        if (!empty($code)) {
            $dataClass[] = 'm--theme-'.$code;
        }

        return implode(' ', $dataClass);
    }

    public function getParagraphId($data): string
    {
        $paragraph = $data->getParagraph();

        return 'paragraph-'.$paragraph->getType().'-'.$paragraph->getId();
    }

    public function getParagraphName($code)
    {
        return $this->paragraphService->getNameByCode($code);
    }

    public function getTextColorSection($data): string
    {
        $paragraph = $data->getParagraph();
        $code      = $paragraph->getColor();

        return empty($code) ? '' : 'm--theme-'.$code;
    }

    public function guardAccessGroupRoutes(Groupe $groupe): bool
    {
        $routes = $this->guardService->getGuardRoutesForGroupe($groupe);

        return 0 != count($routes);
    }

    public function guardAccessUserRoutes(User $user): bool
    {
        $routes = $this->guardService->getGuardRoutesForUser($user);

        return 0 != count($routes);
    }

    public function guardRoute(string $route): bool
    {
        $token = $this->tokenStorage->getToken();

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
     * @param null|string $resolver
     *
     * @return string
     */
    public function imagefilter(
        string $path,
        string $filter,
        array $config = [],
        $resolver = null,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    )
    {
        $url = $this->cacheManager->getBrowserPath(
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

    public function workflowHas(object $entity): bool
    {
        return $this->registry->has($entity);
    }

    protected function setTypeformClass(array $class): string
    {
        if (is_object($class['data'])) {
            $tabClass = explode('\\', $class['data']::class);

            return end($tabClass);
        }

        return $class['form']->vars['unique_block_prefix'];
    }

    private function dump(mixed $var): void
    {
        if ('dev' != $this->getParameter('kernel.debug')) {
            return;
        }

        dump($var);
    }

    private function getFiltersFunctions(): array
    {
        return [
            'paragraph_name'           => 'getParagraphName',
            'paragraph_id'             => 'getParagraphId',
            'block_id'                 => 'getBlockId',
            'paragraph_class'          => 'getParagraphClass',
            'block_class'              => 'getBlockClass',
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

    private function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    /**
     * @return mixed[]
     */
    private function setFilesformClass($type, $class): array
    {
        $htmltwig = '.html.twig';
        $files    = [
            'forms/'.$type.$htmltwig,
        ];

        $vars      = $class->vars;
        $classtype = (isset($vars['value']) && is_object($vars['value'])) ? $vars['value']::class : null;
        if (!is_null($classtype) && 1 == substr_count($classtype, '\Paragraph')) {
            $files[] = 'forms/paragraph/'.$type.$htmltwig;
            $files[] = 'forms/paragraph/default'.$htmltwig;
        }

        if (!is_null($classtype) && 1 == substr_count($classtype, '\Block')) {
            $files[] = 'forms/block/'.$type.$htmltwig;
            $files[] = 'forms/block/default'.$htmltwig;
        }

        $files[] = 'forms/default'.$htmltwig;

        return $files;
    }
}
