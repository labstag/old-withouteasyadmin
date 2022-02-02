<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Edito;
use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;

class EditoTemplatePage extends TemplatePageLib
{
    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        unset($route, $params);

        return $this->router->generate(
            'front',
            [
                'slug' => $page->getFrontslug(),
            ],
            $relative
        );
    }

    public function getId(): string
    {
        return 'edito';
    }

    public function __invoke($matches)
    {
        unset($matches);
        // @var Edito $edito
        $edito = $this->getRepository(Edito::class)->findOnePublier();
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
