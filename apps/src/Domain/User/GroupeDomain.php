<?php

namespace Labstag\Domain\User;

use Labstag\Entity\Groupe;

use Labstag\Form\Gestion\Search\GroupeType as SearchGroupeType;
use Labstag\Form\Gestion\User\GroupeType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\GroupeSearch;

class GroupeDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Groupe::class;
    }

    public function getSearchData(): GroupeSearch
    {
        return new GroupeSearch();
    }

    public function getSearchForm(): string
    {
        return SearchGroupeType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/groupe/index.html.twig',
            'trash'   => 'gestion/user/groupe/index.html.twig',
            'guard'   => 'gestion/guard/group.html.twig',
            'show'    => 'gestion/user/groupe/show.html.twig',
            'preview' => 'gestion/user/groupe/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_groupuser_index'   => $this->translator->trans('groupuser.title', [], 'gestion.breadcrumb'),
            'gestion_groupuser_edit'    => $this->translator->trans('groupuser.edit', [], 'gestion.breadcrumb'),
            'gestion_groupuser_guard'   => $this->translator->trans('groupuser.guard', [], 'gestion.breadcrumb'),
            'gestion_groupuser_new'     => $this->translator->trans('groupuser.new', [], 'gestion.breadcrumb'),
            'gestion_groupuser_trash'   => $this->translator->trans('groupuser.trash', [], 'gestion.breadcrumb'),
            'gestion_groupuser_preview' => $this->translator->trans('groupuser.preview', [], 'gestion.breadcrumb'),
            'gestion_groupuser_show'    => $this->translator->trans('groupuser.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return GroupeType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'gestion_groupuser_edit',
            'empty'   => 'api_action_empty',
            'guard'   => 'gestion_groupuser_guard',
            'list'    => 'gestion_groupuser_index',
            'new'     => 'gestion_groupuser_new',
            'preview' => 'gestion_groupuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'gestion_groupuser_show',
            'trash'   => 'gestion_groupuser_trash',
        ];
    }
}
