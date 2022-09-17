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
        protected Environment $environment
    )
    {
    }

    protected function getBlockFile(string $type): string
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

        $globals = $this->environment->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            dump(['block', $type, $files, $view]);
        }

        return $view;
    }

    protected function getParagraphsArray($service, $content, $paragraphs)
    {
        $paragraphsArray = $content->getParagraphs();
        foreach ($paragraphsArray as $paragraphArray) {
            $data = $service->showContent($paragraphArray);
            if (is_null($data)) {
                continue;
            }

            $paragraphs[] = $data;
        }

        return $paragraphs;
    }
}
