<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Category;
use Labstag\Entity\Edito;
use Labstag\Entity\Libelle;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\TemplatePageLib;

class FrontTemplatePage extends TemplatePageLib
{
    public function __invoke($matches)
    {
        unset($matches);
        $pagination = $this->paginator->paginate(
            $this->getRepository(Post::class)->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/old.html.twig',
            [
                'edito'      => $this->getRepository(Edito::class)->findOnePublier(),
                'pagination' => $pagination,
                'archives'   => $this->getRepository(Post::class)->findDateArchive(),
                'libelles'   => $this->getRepository(Libelle::class)->findByPost(),
                'categories' => $this->getRepository(Category::class)->findByPost(),
            ]
        );
    }

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
}
