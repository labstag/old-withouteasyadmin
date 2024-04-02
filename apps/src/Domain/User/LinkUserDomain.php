<?php

namespace Labstag\Domain\User;

use Labstag\Entity\LinkUser;

use Labstag\Form\Gestion\Search\User\LinkUserType as SearchLinkUserType;
use Labstag\Form\Gestion\User\LinkUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\User\LinkUserSearch;

class LinkUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return LinkUser::class;
    }

    public function getSearchData(): LinkUserSearch
    {
        return new LinkUserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchLinkUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/link/index.html.twig',
            'trash'   => 'gestion/user/link/index.html.twig',
            'show'    => 'gestion/user/link/show.html.twig',
            'preview' => 'gestion/user/link/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_linkuser_index'   => $this->translator->trans('linkuser.title', [], 'gestion.breadcrumb'),
            'gestion_linkuser_edit'    => $this->translator->trans('linkuser.edit', [], 'gestion.breadcrumb'),
            'gestion_linkuser_new'     => $this->translator->trans('linkuser.new', [], 'gestion.breadcrumb'),
            'gestion_linkuser_trash'   => $this->translator->trans('linkuser.trash', [], 'gestion.breadcrumb'),
            'gestion_linkuser_preview' => $this->translator->trans('linkuser.preview', [], 'gestion.breadcrumb'),
            'gestion_linkuser_show'    => $this->translator->trans('linkuser.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return LinkUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'gestion_linkuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'gestion_linkuser_index',
            'new'     => 'gestion_linkuser_new',
            'preview' => 'gestion_linkuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'gestion_linkuser_show',
            'trash'   => 'gestion_linkuser_trash',
        ];
    }
}
