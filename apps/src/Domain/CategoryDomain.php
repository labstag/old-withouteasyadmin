<?php

namespace Labstag\Domain;

use Labstag\Entity\Category;
use Labstag\Form\Gestion\CategoryType;

use Labstag\Form\Gestion\Search\CategoryType as SearchCategoryType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\CategorySearch;

class CategoryDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Category::class;
    }

    public function getMethodsList(): array
    {
        return [
            'trash' => 'findTrashParentForAdmin',
            'all'   => 'findAllParentForAdmin',
        ];
    }

    public function getSearchData(): CategorySearch
    {
        return new CategorySearch();
    }

    public function getSearchForm(): string
    {
        return SearchCategoryType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/category/index.html.twig',
            'trash'   => 'gestion/category/index.html.twig',
            'show'    => 'gestion/category/show.html.twig',
            'preview' => 'gestion/category/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_category_index'   => $this->translator->trans('category.title', [], 'gestion.breadcrumb'),
            'gestion_category_edit'    => $this->translator->trans('category.edit', [], 'gestion.breadcrumb'),
            'gestion_category_new'     => $this->translator->trans('category.new', [], 'gestion.breadcrumb'),
            'gestion_category_preview' => $this->translator->trans('category.preview', [], 'gestion.breadcrumb'),
            'gestion_category_show'    => $this->translator->trans('category.show', [], 'gestion.breadcrumb'),
            'gestion_category_trash'   => $this->translator->trans('category.trash', [], 'gestion.breadcrumb'),
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
            'edit'     => 'gestion_category_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_category_index',
            'new'      => 'gestion_category_new',
            'preview'  => 'gestion_category_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_category_show',
            'trash'    => 'gestion_category_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
