<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_category_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_category_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Category $category,
        CategoryRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            CategoryType::class,
            !is_null($category) ? $category : new Category()
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
            'admin/category/index.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_category_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_category_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Category $category
    ): Response
    {
        return $this->renderShowOrPreview(
            $category,
            'admin/category/show.html.twig'
        );
    }

    protected function getMethodsList(): array
    {
        return [
            'trash' => 'findTrashParentForAdmin',
            'all'   => 'findAllParentForAdmin',
        ];
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_category_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_category_index',
            'new'      => 'admin_category_new',
            'preview'  => 'admin_category_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_category_show',
            'trash'    => 'admin_category_trash',
            'workflow' => 'api_action_workflow',
        ];
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
