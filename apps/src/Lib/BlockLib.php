<?php

namespace Labstag\Lib;

use Labstag\Entity\Paragraph;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Service\FrontService;
use Labstag\Service\MenuService;
use Labstag\Service\ParagraphService;
use Labstag\Service\RepositoryService;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockLib extends AbstractController
{
    public function __construct(
        protected RepositoryService $repositoryService,
        protected MenuService $menuService,
        protected ParagraphService $paragraphService,
        protected FrontService $frontService,
        protected RequestStack $requestStack,
        protected RouterInterface $router,
        protected TranslatorInterface $translator,
        protected Environment $twigEnvironment,
        protected array $template = []
    ) {
    }

    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

        return '';
    }

    public function template(
        EntityBlockInterface $entityBlock,
        ?EntityFrontInterface $entityFront
    ): array {
        return $this->showTemplateFile($this->getCode($entityBlock, $entityFront));
    }

    protected function getParagraphsArray(
        ParagraphService $paragraphService,
        EntityFrontInterface $entityFront,
        array $paragraphs
    ): array {
        $methods = get_class_methods($entityFront);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        $paragraphsArray = $entityFront->getParagraphs();
        foreach ($paragraphsArray as $paragraphArray) {
            /** @var Paragraph $paragraphArray */
            $data = $paragraphService->showContent($paragraphArray);
            if (is_null($data)) {
                continue;
            }

            $template = $paragraphService->showTemplate($paragraphArray);

            $paragraphs[] = [
                'template' => $template,
                'data'     => $data,
            ];
        }

        return $paragraphs;
    }

    protected function getTemplateData(string $type): array
    {
        if (isset($this->template[$type])) {
            return $this->template[$type];
        }

        $loader   = $this->twigEnvironment->getLoader();
        $htmltwig = '.html.twig';

        $files = [
            'block/'.$type.$htmltwig,
            'block/default'.$htmltwig,
        ];

        $view = end($files);

        foreach ($files as $file) {
            if (!$loader->exists($file)) {
                continue;
            }

            $view = $file;

            break;
        }

        $this->template[$type] = [
            'hook'  => 'block',
            'type'  => $type,
            'files' => $files,
            'view'  => $view,
        ];

        return $this->template[$type];
    }

    protected function getTemplateFile(string $type): string
    {
        $data = $this->getTemplateData($type);

        return $data['view'];
    }

    protected function showTemplateFile(string $type): array
    {
        $data    = $this->getTemplateData($type);
        $globals = $this->twigEnvironment->getGlobals();
        if (!isset($globals['app'])) {
            return [];
        }

        $app = $globals['app'];
        if ($app instanceof AppVariable && 'dev' == $app->getDebug()) {
            return $data;
        }

        return [];
    }
}
