<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Form\Admin\PostType;
use Labstag\Form\Admin\Search\PostType as SearchPostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\Search\PostSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_post_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_post_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Post $post,
        PostRequestHandler $requestHandler
    ): Response
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
     * @Route("/trash", name="admin_post_trash", methods={"GET"})
     * @Route("/", name="admin_post_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Post::class,
            'admin/post/index.html.twig',
        );
    }

    /**
     * @Route("/{id}", name="admin_post_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_post_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Post $post
    ): Response
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

    protected function setBreadcrumbsPageAdminPost(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.title', [], 'admin.breadcrumb'),
                'route' => 'admin_post_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_post_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.new', [], 'admin.breadcrumb'),
                'route' => 'admin_post_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostPreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_post_trash',
            ],
            [
                'title' => $this->translator->trans('post.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_post_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.show', [], 'admin.breadcrumb'),
                'route' => 'admin_post_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_post_trash',
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
