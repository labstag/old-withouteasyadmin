<?php

namespace Labstag\Lib;

use Labstag\Service\ParagraphService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockLib extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected Environment $twigEnvironment,
        protected array $template = []
    )
    {
    }

    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

        return '';
    }

    public function template(
        EntityBlockLib $entityBlockLib,
        ?EntityPublicLib $entityPublicLib
    ): array
    {
        return $this->showTemplateFile($this->getCode($entityBlockLib, $entityPublicLib));
    }

    protected function getParagraphsArray(
        ParagraphService $paragraphService,
        EntityPublicLib $entityPublicLib,
        array $paragraphs
    ): array
    {
        $methods = get_class_methods($entityPublicLib);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        $paragraphsArray = $entityPublicLib->getParagraphs();
        foreach ($paragraphsArray as $paragraphArray) {
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

        $folder = __DIR__.'/../../templates/';
        $htmltwig = '.html.twig';

        $files = [
            'block/'.$type.$htmltwig,
            'block/default'.$htmltwig,
        ];

        $view = end($files);

        foreach ($files as $file) {
            if (is_file($folder.$file)) {
                $view = $file;

                break;
            }
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
        $data = $this->getTemplateData($type);
        $globals = $this->twigEnvironment->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            return $data;
        }

        return [];
    }
}
