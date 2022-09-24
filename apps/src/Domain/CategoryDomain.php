<?php

namespace Labstag\Domain;

use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;

use Labstag\Form\Admin\Search\CategoryType as SearchCategoryType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\CategoryRepository;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\Search\CategorySearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoryDomain extends DomainLib
{
    public function __construct(
        protected CategoryRequestHandler $categoryRequestHandler,
        protected CategoryRepository $categoryRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Category::class;
    }

    public function getRepository()
    {
        return $this->categoryRepository;
    }

    public function getRequestHandler()
    {
        return $this->categoryRequestHandler;
    }

    public function getSearchData()
    {
        return CategorySearch::class;
    }

    public function getSearchForm()
    {
        return SearchCategoryType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_category_index'   => $this->translator->trans('category.title', [], 'admin.breadcrumb'),
            'admin_category_edit'    => $this->translator->trans('category.edit', [], 'admin.breadcrumb'),
            'admin_category_new'     => $this->translator->trans('category.new', [], 'admin.breadcrumb'),
            'admin_category_preview' => $this->translator->trans('category.preview', [], 'admin.breadcrumb'),
            'admin_category_show'    => $this->translator->trans('category.show', [], 'admin.breadcrumb'),
            'admin_category_trash'   => $this->translator->trans('category.trash', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return CategoryType::class;
    }

    public function getUrlAdmin(): array
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
}
