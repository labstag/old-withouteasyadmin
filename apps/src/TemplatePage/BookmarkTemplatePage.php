<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Bookmark;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookmarkTemplatePage extends TemplatePageLib
{
    public function category(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublierCategory($code),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
    }

    public function index()
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublier(),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/index.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
    }

    public function libelle(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublierLibelle($code),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
    }

    public function show(Bookmark $bookmark)
    {
        return new RedirectResponse($bookmark->getUrl(), 302);
    }
}
