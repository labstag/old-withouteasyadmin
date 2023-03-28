<?php

namespace Labstag\Domain;

use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;

use Labstag\Form\Admin\Search\CategoryType as SearchCategoryType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\CategorySearch;

class CategoryDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Category::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->categoryRepository;
    }

    public function getSearchData(): CategorySearch
    {
        return $this->categorySearch;
    }

    public function getSearchForm(): string
    {
        return SearchCategoryType::class;
    }

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

    public function getType(): string
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
