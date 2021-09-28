<?php

namespace Labstag\Controller;

use Labstag\Entity\Bookmark;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bookmark")
 */
class BookmarkController extends FrontControllerLib
{

    /**
     * @Route("/category/{code}", name="bookmark_category")
     *
     * @return void
     */
    public function category(
        BookmarkRepository $bookmarkRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $bookmarkRepository->findPublierCategory($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $libelleRepository->findByBookmark(),
                'categories' => $categoryRepository->findByBookmark(),
            ]
        );
    }

    /**
     * @Route("/libelle/{code}", name="bookmark_libelle")
     *
     * @return void
     */
    public function libelle(
        BookmarkRepository $bookmarkRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $bookmarkRepository->findPublierLibelle($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $libelleRepository->findByBookmark(),
                'categories' => $categoryRepository->findByBookmark(),
            ]
        );
    }

    /**
     * @Route("/", name="bookmark_ndex")
     */
    public function index(
        Request $request,
        BookmarkRepository $bookmarkRepository,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        $pagination = $this->paginator->paginate(
            $bookmarkRepository->findPublier(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/index.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $libelleRepository->findByBookmark(),
                'categories' => $categoryRepository->findByBookmark(),
            ]
        );
    }

    /**
     * @Route("/{slug}", name="bookmark_show")
     */
    public function show(
        Bookmark $bookmark
    )
    {
        return $this->redirect($bookmark->getUrl());
    }
}
