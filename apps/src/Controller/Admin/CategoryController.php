<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;
use Labstag\Form\Admin\Search\CategoryType as SearchCategoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\Search\CategorySearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/category')]
class CategoryController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        ?Category $category,
        CategoryRequestHandler $categoryRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $attachFormService,
            $categoryRequestHandler,
            CategoryType::class,
            is_null($category) ? new Category() : $category
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_category_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_category_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Category::class,
            'admin/category/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_category_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_category_preview', methods: ['GET'])]
    public function showOrPreview(Category $category): Response
    {
        return $this->renderShowOrPreview(
            $category,
            'admin/category/show.html.twig'
        );
    }

    /**
     * @return array<string, string>
     */
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

    /**
     * @return array<string, class-string<\Labstag\Form\Admin\Search\CategoryType>>|array<string, \CategorySearch>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchCategoryType::class,
            'data' => new CategorySearch(),
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
                    'title' => $this->translator->trans('category.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_index',
                ],
                [
                    'title' => $this->translator->trans('category.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_edit',
                ],
                [
                    'title' => $this->translator->trans('category.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_new',
                ],
                [
                    'title' => $this->translator->trans('category.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_preview',
                ],
                [
                    'title' => $this->translator->trans('category.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_show',
                ],
                [
                    'title' => $this->translator->trans('category.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_category_trash',
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

        return [...$headers, ...[
            'admin_category' => $this->translator->trans('category.title', [], 'admin.header'),
        ]];
    }
}
