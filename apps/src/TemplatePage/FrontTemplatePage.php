<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;

class FrontTemplatePage extends TemplatePageLib
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
        return 'front';
    }

    public function launch($matches)
    {
        unset($matches);
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
