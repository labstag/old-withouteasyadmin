<?php

namespace Labstag\Controller;

use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Service\TemplatePageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="post_archive")
     */
    public function archive(
        TemplatePageService $templatePageService,
        Request $request,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\PostTemplatePage';
        $method    = 'archive';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/category/{code}", name="post_category")
     *
     * @return void
     */
    public function category(
        TemplatePageService $templatePageService,
        Request $request,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\PostTemplatePage';
        $method    = 'category';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/libelle/{code}", name="post_libelle")
     *
     * @return void
     */
    public function libelle(
        TemplatePageService $templatePageService,
        Request $request,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\PostTemplatePage';
        $method    = 'libelle';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/{slug}", name="post_show")
     */
    public function show(
        TemplatePageService $templatePageService,
        Request $request,
        Post $post,
    )
    {
        $className = 'Labstag\TemplatePage\PostTemplatePage';
        $method    = 'show';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($post);
    }

    /**
     * @Route("/user/{username}", name="post_user")
     */
    public function user(
        TemplatePageService $templatePageService,
        Request $request,
        $username
    )
    {
        $className = 'Labstag\TemplatePage\PostTemplatePage';
        $method    = 'category';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($username);
    }
}
