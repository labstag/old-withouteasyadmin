<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Post\PostType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\PostRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\PostRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AdminControllerLib
{

    protected string $headerTitle = 'Post';

    /**
     * @Route("/{id}/edit", name="admin_post_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Post $post,
        PostRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            PostType::class,
            $post,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_post_index',
                'show'   => 'admin_post_show',
            ],
            'admin/post/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_post_trash", methods={"GET"})
     * @Route("/",       name="admin_post_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(PostRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/post/index.html.twig',
            [
                'new'   => 'admin_post_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_post_trash',
                'list'  => 'admin_post_index',
            ],
            [
                'list'     => 'admin_post_index',
                'show'     => 'admin_post_show',
                'preview'  => 'admin_post_preview',
                'edit'     => 'admin_post_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_post_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        PostRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Post(),
            PostType::class,
            ['list' => 'admin_post_index'],
            'admin/post/form.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_post_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_post_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Post $post
    ): Response
    {
        return $this->renderShowOrPreview(
            $post,
            'admin/post/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_post_edit',
                'list'    => 'admin_post_index',
                'trash'   => 'admin_post_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminPost(): array
    {
        return [
            [
                'title'        => $this->translator->trans('post.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('post.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('post.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('post.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('post.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPostTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_post_trash',
                'route_params' => [],
            ],
        ];
    }
}
