<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\PostRepository;
use Labstag\RequestHandler\PostRequestHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/post')]
class PostController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Post $post
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($post) ? new Post() : $post,
            'admin/post/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_post_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_post_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/post/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_post_new', methods: ['GET', 'POST'])]
    public function new(
        PostRepository $postRepository,
        PostRequestHandler $postRequestHandler,
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

        $old = clone $post;
        $postRepository->add($post);
        $postRequestHandler->handle($old, $post);

        return $this->redirectToRoute('admin_post_edit', ['id' => $post->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_post_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_post_preview', methods: ['GET'])]
    public function showOrPreview(Post $post): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $post,
            'admin/post/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Post::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
