<?php

namespace Labstag\Controller;

use Labstag\Entity\Bookmark;
use Labstag\Lib\FrontControllerLib;
use Labstag\Service\TemplatePageService;
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
        TemplatePageService $templatePageService,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\BookmarkTemplatePage';
        $method    = 'category';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($code);
    }

    /**
     * @Route("/", name="bookmark_index")
     */
    public function index(
        TemplatePageService $templatePageService
    ): Response
    {
        $className = 'Labstag\TemplatePage\BookmarkTemplatePage';
        $method    = 'index';

        $class = $templatePageService->getClass($className);

        return $class->{$method}();
    }

    /**
     * @Route("/libelle/{code}", name="bookmark_libelle")
     *
     * @return void
     */
    public function libelle(
        TemplatePageService $templatePageService,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\BookmarkTemplatePage';
        $method    = 'libelle';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($code);
    }

    /**
     * @Route("/{slug}", name="bookmark_show")
     */
    public function show(
        TemplatePageService $templatePageService,
        Bookmark $bookmark
    )
    {
        $className = 'Labstag\TemplatePage\BookmarkTemplatePage';
        $method    = 'show';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($bookmark);
    }
}
