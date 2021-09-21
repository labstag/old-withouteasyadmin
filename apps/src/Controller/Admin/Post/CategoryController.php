<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Category;
use Labstag\Form\Admin\Post\CategoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\CategoryRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\CategoryRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/category")
 */
class CategoryController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_category_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Category $category,
        CategoryRequestHandler $requestHandler
    ): Response {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            CategoryType::class,
            $category,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_category_index',
                'show'   => 'admin_category_show',
            ]
        );
    }

    /**
     * @Route("/trash",  name="admin_category_trash", methods={"GET"})
     * @Route("/",       name="admin_category_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(CategoryRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashParentForAdmin',
                'all'   => 'findAllParentForAdmin',
            ],
            'admin/post/category/index.html.twig',
            [
                'new'   => 'admin_category_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_category_trash',
                'list'  => 'admin_category_index',
            ],
            [
                'list'     => 'admin_category_index',
                'show'     => 'admin_category_show',
                'preview'  => 'admin_category_preview',
                'edit'     => 'admin_category_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_category_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        CategoryRequestHandler $requestHandler
    ): Response {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Category(),
            CategoryType::class,
            ['list' => 'admin_category_index']
        );
    }

    /**
     * @Route("/{id}",         name="admin_category_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_category_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Category $category
    ): Response {
        return $this->renderShowOrPreview(
            $category,
            'admin/post/category/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_category_edit',
                'list'    => 'admin_category_index',
                'trash'   => 'admin_category_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminCategory(): array
    {
        return [
            [
                'title'        => $this->translator->trans('category.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminCategoryEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('category.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminCategoryNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('category.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminCategoryPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('category.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('category.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminCategoryShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('category.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminCategoryTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('category.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_category_trash',
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
                'admin_category' => $this->translator->trans('category.title', [], 'admin.header'),
            ]
        );
    }
}
