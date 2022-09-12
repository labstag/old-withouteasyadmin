<?php

namespace Labstag\Lib;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class BlockLib extends AbstractController
{

    protected Request $request;

    public function __construct(
        protected TranslatorInterface $translator,
        protected Environment $twig
    )
    {
    }

    protected function getBlockFile(string $type)
    {
        $folder   = __DIR__.'/../../templates/';
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

        $globals = $this->twig->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            dump(['block', $type, $files, $view]);
        }

        return $view;
    }

    protected function getParagraphsArray($service, $content, $paragraphs)
    {
        $paragraphsArray = $content->getParagraphs();
        foreach ($paragraphsArray as $paragraph) {
            $data = $service->showContent($paragraph);
            if (is_null($data)) {
                continue;
            }

            $paragraphs[] = $data;
        }

        return $paragraphs;
    }
}
