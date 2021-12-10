<?php

namespace Labstag\TemplatePage;

use Labstag\Lib\TemplatePageLib;

class FrontTemplatePage extends TemplatePageLib
{
    public function index()
    {
        return $this->launch('', '');
    }

    public function launch($matches, $slug)
    {
        unset($matches, $slug);
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
