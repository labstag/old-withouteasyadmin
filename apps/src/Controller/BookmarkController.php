<?php

namespace Labstag\Controller;

use Labstag\Entity\Bookmark;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Repository\Paragraph\BookmarkRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mes-liens')]
class BookmarkController extends FrontControllerLib
{
    #[Route(
        path: '/{slug}',
        name: 'front_bookmark',
        priority: 2
    )]
    public function bookmark(
        string $slug,
        BookmarkRepository $bookmarkRepo,
        PageRepository $pageRepo
    )
    {
        $bookmark = $bookmarkRepo->findOneBy(
            ['slug' => $slug]
        );

        if (!$bookmark instanceof Bookmark) {
            return $this->page('mes-liens/'.$slug, $pageRepo);
        }

        return new RedirectResponse($bookmark->getUrl(), 302);
    }

    #[Route(
        path: '/categorie/{slug}',
        name: 'front_bookmark_category',
        priority: 2
    )]
    public function category(
        string $slug
    )
    {
        unset($slug);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }

    #[Route(
        path: '/libelle/{code}',
        name: 'front_bookmark_libelle',
        priority: 2
    )]
    public function libelle(
        string $code
    )
    {
        unset($code);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }
}
