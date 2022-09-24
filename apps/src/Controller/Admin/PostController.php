<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Search\PostType as SearchPostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PostRepository;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\Search\PostSearch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/post')]
class PostController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Post $post,
        PostRequestHandler $postRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            is_null($post) ? new Post() : $post,
            'admin/post/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_post_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_post_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
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

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_post_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_post_preview', methods: ['GET'])]
    public function showOrPreview(Post $post): Response
    {
        return $this->renderShowOrPreview(
            $post,
            'admin/post/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Post::class);
    }

    /**
     * @return array<string, \PostSearch>|array<string, class-string<\Labstag\Form\Admin\Search\PostType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchPostType::class,
            'data' => new PostSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('post.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_index',
                ],
                [
                    'title' => $this->translator->trans('post.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_edit',
                ],
                [
                    'title' => $this->translator->trans('post.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_new',
                ],
                [
                    'title' => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_trash',
                ],
                [
                    'title' => $this->translator->trans('post.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_preview',
                ],
                [
                    'title' => $this->translator->trans('post.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_post_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [
            ...$headers, ...
            [
                'admin_post' => $this->translator->trans('post.title', [], 'admin.header'),
            ],
        ];
    }
}
