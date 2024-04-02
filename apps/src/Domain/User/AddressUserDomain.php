<?php

namespace Labstag\Domain\User;

use Labstag\Entity\AddressUser;

use Labstag\Form\Gestion\Search\User\AddressUserType as SearchAddressUserType;
use Labstag\Form\Gestion\User\AddressUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\User\AddressUserSearch;

class AddressUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return AddressUser::class;
    }

    public function getSearchData(): AddressUserSearch
    {
        return new AddressUserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchAddressUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/address/index.html.twig',
            'trash'   => 'gestion/user/address/index.html.twig',
            'show'    => 'gestion/user/address/show.html.twig',
            'preview' => 'gestion/user/address/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_addressuser_index'   => $this->translator->trans('addressuser.title', [], 'gestion.breadcrumb'),
            'gestion_addressuser_edit'    => $this->translator->trans('addressuser.edit', [], 'gestion.breadcrumb'),
            'gestion_addressuser_new'     => $this->translator->trans('addressuser.new', [], 'gestion.breadcrumb'),
            'gestion_addressuser_trash'   => $this->translator->trans('addressuser.trash', [], 'gestion.breadcrumb'),
            'gestion_addressuser_preview' => $this->translator->trans('addressuser.preview', [], 'gestion.breadcrumb'),
            'gestion_addressuser_show'    => $this->translator->trans('addressuser.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return AddressUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'gestion_addressuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'gestion_addressuser_index',
            'new'     => 'gestion_addressuser_new',
            'preview' => 'gestion_addressuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'gestion_addressuser_show',
            'trash'   => 'gestion_addressuser_trash',
        ];
    }
}
