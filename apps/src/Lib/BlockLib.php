<?php

namespace Labstag\Lib;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockLib extends AbstractController
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected Environment $environment,
        protected array $template = []
    )
    {
    }

    public function getCode($block, $content): string
    {
        unset($block, $content);

        return '';
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
            'hook' => 'block',
            'type' => $type,
            'files' => $files,
            'view' => $view,
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
        $globals = $this->environment->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            return $data;
        }

        return [];
    }

    public function template($entity, $content)
    {
        return $this->showTemplateFile($this->getCode($entity, $content));
    }

    protected function getParagraphsArray($service, $content, $paragraphs)
    {
        $paragraphsArray = $content->getParagraphs();
        foreach ($paragraphsArray as $paragraphArray) {
            $data = $service->showContent($paragraphArray);
            if (is_null($data)) {
                continue;
            }

            $template = $service->showTemplate($paragraphArray);

            $paragraphs[] = [
                'template' => $template,
                'data' => $data
            ];
        }

        return $paragraphs;
    }
}
