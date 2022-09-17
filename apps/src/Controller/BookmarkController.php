<?php

namespace Labstag\Controller;

use Labstag\Entity\Bookmark;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\PageRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mes-liens')]
class BookmarkController extends FrontControllerLib
{
    #[Route(
        path: '/{slug}',
        name: 'front_bookmark',
        priority: 2,
        defaults: ['slug' => '']
    )]
    public function bookmark(
        string $slug,
        BookmarkRepository $bookmarkRepository,
        PageRepository $pageRepository
    )
    {
        $bookmark = $bookmarkRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$bookmark instanceof Bookmark) {
            if ('' != $slug) {
                throw $this->createNotFoundException();
            }

            return $this->page('mes-liens', $pageRepository);
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
        path: '/libelle/{slug}',
        name: 'front_bookmark_libelle',
        priority: 2
    )]
    public function libelle(
        string $slug
    )
    {
        unset($slug);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }
}
