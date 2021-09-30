<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Bookmark;
use Labstag\Form\Admin\BookmarkType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\BookmarkRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/bookmark")
 */
class BookmarkController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_bookmark_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Bookmark $bookmark,
        BookmarkRequestHandler $requestHandler
    ): Response {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            BookmarkType::class,
            $bookmark,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_bookmark_index',
                'show'   => 'admin_bookmark_show',
            ],
            'admin/bookmark/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_bookmark_trash", methods={"GET"})
     * @Route("/",       name="admin_bookmark_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(BookmarkRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/bookmark/index.html.twig',
            [
                'new'   => 'admin_bookmark_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_bookmark_trash',
                'list'  => 'admin_bookmark_index',
            ],
            [
                'list'     => 'admin_bookmark_index',
                'show'     => 'admin_bookmark_show',
                'preview'  => 'admin_bookmark_preview',
                'edit'     => 'admin_bookmark_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_bookmark_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        BookmarkRequestHandler $requestHandler
    ): Response {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Bookmark(),
            BookmarkType::class,
            ['list' => 'admin_bookmark_index'],
            'admin/bookmark/form.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_bookmark_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_bookmark_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Bookmark $bookmark
    ): Response {
        return $this->renderShowOrPreview(
            $bookmark,
            'admin/bookmark/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_bookmark_edit',
                'list'    => 'admin_bookmark_index',
                'trash'   => 'admin_bookmark_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminBookmark(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('bookmark.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_bookmark' => $this->translator->trans('bookmark.title', [], 'admin.header'),
            ]
        );
    }
}
