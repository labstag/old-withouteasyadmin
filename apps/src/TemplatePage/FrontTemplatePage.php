<?php

namespace Labstag\TemplatePage;

use Labstag\Lib\TemplatePageLib;

class FrontTemplatePage extends TemplatePageLib
{
    public function edito()
    {
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

    public function index()
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/index.html.twig',
            [
                'edito'      => $this->editoRepository->findOnePublier(),
                'pagination' => $pagination,
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }
}
