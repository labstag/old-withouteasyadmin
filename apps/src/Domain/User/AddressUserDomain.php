<?php

namespace Labstag\Domain\User;

use Labstag\Entity\AddressUser;

use Labstag\Form\Admin\Search\User\AddressUserType as SearchAddressUserType;
use Labstag\Form\Admin\User\AddressUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\AddressUserRepository;
use Labstag\Search\User\AddressUserSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddressUserDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected AddressUserRepository $addressUserRepository,
        protected AddressUserSearch $addressUserSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return AddressUser::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->addressUserRepository;
    }

    public function getSearchData(): AddressUserSearch
    {
        return $this->addressUserSearch;
    }

    public function getSearchForm(): string
    {
        return SearchAddressUserType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_addressuser_index'   => $this->translator->trans('addressuser.title', [], 'admin.breadcrumb'),
            'admin_addressuser_edit'    => $this->translator->trans('addressuser.edit', [], 'admin.breadcrumb'),
            'admin_addressuser_new'     => $this->translator->trans('addressuser.new', [], 'admin.breadcrumb'),
            'admin_addressuser_trash'   => $this->translator->trans('addressuser.trash', [], 'admin.breadcrumb'),
            'admin_addressuser_preview' => $this->translator->trans('addressuser.preview', [], 'admin.breadcrumb'),
            'admin_addressuser_show'    => $this->translator->trans('addressuser.show', [], 'admin.breadcrumb'),
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
            'edit'    => 'admin_addressuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_addressuser_index',
            'new'     => 'admin_addressuser_new',
            'preview' => 'admin_addressuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_addressuser_show',
            'trash'   => 'admin_addressuser_trash',
        ];
    }
}
