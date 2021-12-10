<?php

namespace Labstag\TemplatePage;

use Labstag\Lib\TemplatePageLib;

class EditoTemplatePage extends TemplatePageLib
{
    public function launch($matches, $slug)
    {
        unset($matches, $slug);
        // @var Edito $edito
        $edito = $this->editoRepository->findOnePublier();
        $this->setMetaOpenGraph(
            $edito->getTitle(),
            $edito->getMetaKeywords(),
            $edito->getMetaDescription(),
            $edito->getFond()
        );

        return $this->render(
            'front/edito.html.twig',
            ['edito' => $edito]
        );
    }
}
