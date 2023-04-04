<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PostRepository;
use Labstag\Service\AdminService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/post', name: 'admin_post_')]
class PostController extends AdminControllerLib
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
    public function new(
        PostRepository $postRepository,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('admin_post_index');
        }

        $post = new Post();
        $post->setPublished(new DateTime());
        $post->setRemark(false);
        $post->setTitle(Uuid::v1());
        $post->setRefuser($user);

        $postRepository->save($post);

        return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
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

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Post::class);

        return $this->adminService;
    }
}
