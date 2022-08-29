<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Form\Admin\Search\PostType as SearchPostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PostRepository;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\Search\PostSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/post')]
class PostController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_post_edit', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Post $post, PostRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            PostType::class,
            !is_null($post) ? $post : new Post(),
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
            Post::class,
            'admin/post/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_post_new', methods: ['GET', 'POST'])]
    public function new(PostRepository $repository, PostRequestHandler $requestHandler, Security $security): Response
    {
        $user = $user = $security->getUser();
        $post = new Post();
        $post->setPublished(new DateTime());
        $post->setRemark(false);
        $post->setTitle(Uuid::v1());
        $post->setRefuser($user);
        $old = clone $post;
        $repository->add($post);
        $requestHandler->handle($old, $post);

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

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_post_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_post_index',
            'new'      => 'admin_post_new',
            'preview'  => 'admin_post_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_post_show',
            'trash'    => 'admin_post_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchPostType::class,
            'data' => new PostSearch(),
        ];
    }

    protected function setBreadcrumbsData(): array
    {
        return [
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
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_post' => $this->translator->trans('post.title', [], 'admin.header'),
            ]
        );
    }
}
