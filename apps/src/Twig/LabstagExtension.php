<?php

namespace Labstag\Twig;

use Labstag\Entity\Attachment;
use Labstag\Entity\Block;
use Labstag\Entity\Groupe;
use Labstag\Entity\Paragraph;
use Labstag\Entity\Route;
use Labstag\Entity\User;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Repository\AttachmentRepository;
use Labstag\Service\GuardService;
use Labstag\Service\ParagraphService;
use Labstag\Service\PhoneService;
use Labstag\Service\WorkflowService;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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

    protected array $templates = [];

    public function __construct(
        protected ContainerBagInterface $containerBag,
        protected RouterInterface $router,
        protected PhoneService $phoneService,
        protected CacheManager $cacheManager,
        protected WorkflowService $workflowService,
        protected TokenStorageInterface $tokenStorage,
        protected LoggerInterface $logger,
        protected GuardService $guardService,
        protected ParagraphService $paragraphService,
        protected AttachmentRepository $attachmentRepository
    )
    {
    }

    public function classEntity(object $entity): string
    {
        $class = substr(
            (string) $entity::class,
            strpos((string) $entity::class, self::FOLDER_ENTITY) + strlen(self::FOLDER_ENTITY)
        );

        return trim(strtolower($class));
    }

    public function debugBegin(array $data): string
    {
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugBeginForm(mixed $class): string
    {
        $data = $this->getformClassData($class);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugBeginPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->beginDebug($data);
    }

    public function debugEnd(array $data): string
    {
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    public function debugEndForm(mixed $class): string
    {
        $data = $this->getformClassData($class);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    public function debugEndPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);
        if (0 == (is_countable($data) ? count($data) : 0)) {
            return '';
        }

        return $this->endDebug($data);
    }

    public function formClass(mixed $class): string
    {
        $data = $this->getformClassData($class);

        return $data['view'];
    }

    public function formPrototype(array $blockPrefixes): string
    {
        $data = $this->formPrototypeData($blockPrefixes);

        return $data['view'];
    }

    public function getAttachment(mixed $data): ?Attachment
    {
        if (is_null($data)) {
            return null;
        }

        $id         = $data->getId();
        $attachment = $this->attachmentRepository->findOneBy(['id' => $id]);
        if (is_null($attachment)) {
            return null;
        }

        /** @var Attachment $attachment */
        $name = (string) $attachment->getName();
        if (!is_file($name)) {
            return null;
        }

        return $attachment;
    }

    public function getBlockClass(BlockInterface $entityBlockLib): string
    {
        /** @var Block $block */
        $block = $entityBlockLib->getBlock();

        return 'block-'.$block->getType();
    }

    public function getBlockId(BlockInterface $entityBlockLib): string
    {
        /** @var Block $block */
        $block = $entityBlockLib->getBlock();

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
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable, ['is_safe' => ['all']]);
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
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable, ['is_safe' => ['all']]);
        }

        return $functions;
    }

    public function getParagraphClass(ParagraphInterface $entityParagraphLib): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraphLib->getParagraph();
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

    public function getParagraphId(ParagraphInterface $entityParagraphLib): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraphLib->getParagraph();

        return 'paragraph-'.$paragraph->getType().'-'.$paragraph->getId();
    }

    public function getParagraphName(string $code): string
    {
        return $this->paragraphService->getNameByCode($code);
    }

    public function getTextColorSection(ParagraphInterface $entityParagraphLib): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraphLib->getParagraph();
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

    public function guardRouteEnableGroupe(Route $route, Groupe $groupe): bool
    {
        return $this->guardService->guardRouteEnableGroupe($route, $groupe);
    }

    public function guardRouteEnableUser(Route $route, User $user): bool
    {
        return $this->guardService->guardRouteEnableUser($route, $user);
    }

    public function imagefilter(
        string $path,
        string $filter,
        array $config = [],
        ?string $resolver = null,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string
    {
        $url = $this->cacheManager->getBrowserPath(
            (string) parse_url($path, PHP_URL_PATH),
            $filter,
            $config,
            $resolver,
            $referenceType
        );

        return (string) parse_url($url, PHP_URL_PATH);
    }

    public function verifPhone(string $country, string $phone): bool
    {
        $verif = $this->phoneService->verif($phone, $country);

        return array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
    }

    public function workflowHas(object $entity): bool
    {
        return $this->workflowService->has($entity);
    }

    protected function setTypeformClass(array $class): string
    {
        if (is_object($class['data'])) {
            $tabClass = explode('\\', $class['data']::class);

            return end($tabClass);
        }

        return $class['form']->vars['unique_block_prefix'];
    }

    private function beginDebug(array $data): string
    {
        $html = "<!--\nTHEME DEBUG\n";
        $html .= "THEME HOOK : '".$data['hook']."'\n";
        if (0 != (is_countable($data['files']) ? count($data['files']) : 0)) {
            $html .= "FILE NAME SUGGESTIONS: \n";
            foreach ($data['files'] as $file) {
                $checked = ($data['view'] == $file) ? 'X' : '*';
                $html .= ' '.$checked.' '.$file."\n";
            }
        }

        return $html.("BEGIN OUTPUT from '".$data['view']."' -->\n");
    }

    private function endDebug(array $data): string
    {
        return "\n<!-- END OUTPUT from '".$data['view']."' -->\n";
    }

    private function formPrototypeData(array $blockPrefixes): array
    {
        $type = ('collection_entry' != $blockPrefixes[1]) ? $blockPrefixes[2] : 'other';
        if (isset($this->templates['prototype'][$type])) {
            return $this->templates['prototype'][$type];
        }

        $files = [
            'prototype/'.$type.'.html.twig',
            'prototype/default.html.twig',
        ];

        $folder = __DIR__.'/../../templates/';
        $view   = end($files);
        foreach ($files as $file) {
            if (is_file($folder.$file)) {
                $view = $file;

                break;
            }
        }

        $this->templates['prototype'][$type] = [
            'hook'  => 'prototype',
            'type'  => $type,
            'files' => $files,
            'view'  => $view,
        ];

        return $this->templates['prototype'][$type];
    }

    private function getFiltersFunctions(): array
    {
        return [
            'debug_begin_prototype'    => 'debugBeginPrototype',
            'debug_end_prototype'      => 'debugEndPrototype',
            'debug_begin_form'         => 'debugBeginForm',
            'debug_end_form'           => 'debugEndForm',
            'debug_begin'              => 'debugBegin',
            'debug_end'                => 'debugEnd',
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
            'verifPhone'               => 'verifPhone',
            'workflow_has'             => 'workflowHas',
        ];
    }

    private function getformClassData(mixed $class): array
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
        if (isset($this->templates['form'][$type])) {
            return $this->templates['form'][$type];
        }

        $files = $this->setFilesformClass($type, $class);
        $view  = end($files);

        foreach ($files as $file) {
            if (is_file($folder.$file)) {
                $view = $file;

                break;
            }
        }

        $this->templates['form'][$type] = [
            'hook'  => 'form',
            'type'  => $type,
            'files' => $files,
            'view'  => $view,
        ];

        return $this->templates['form'][$type];
    }

    /**
     * @return mixed[]
     */
    private function setFilesformClass(
        string $type,
        object $class
    ): array
    {
        $htmltwig = '.html.twig';
        $files    = [
            'forms/'.$type.$htmltwig,
        ];

        if (isset($class->vars)) {
            /** @var array $vars */
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
        }

        $files[] = 'forms/default'.$htmltwig;

        return $files;
    }
}
