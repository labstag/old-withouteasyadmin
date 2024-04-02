<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\PostService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/post', name: 'admin_post_')]
class PostController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Post $post
    ): Response
    {
        return $this->setAdmin()->edit($post);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Security $security): RedirectResponse
    {
        return $this->setAdmin()->add($security);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Post $post): Response
    {
        return $this->setAdmin()->preview($post);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->setAdmin()->show($post);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): PostService
    {
        $viewService = $this->gestionService->setDomain(Post::class);
        if (!$viewService instanceof PostService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
