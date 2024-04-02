<?php

namespace Labstag\Domain\User;

use Labstag\Entity\PhoneUser;

use Labstag\Form\Gestion\Search\User\PhoneUserType as SearchPhoneUserType;
use Labstag\Form\Gestion\User\PhoneUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\User\PhoneUserSearch;

class PhoneUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return PhoneUser::class;
    }

    public function getSearchData(): PhoneUserSearch
    {
        return new PhoneUserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchPhoneUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/phone/index.html.twig',
            'trash'   => 'gestion/user/phone/index.html.twig',
            'show'    => 'gestion/user/phone/show.html.twig',
            'preview' => 'gestion/user/phone/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_phoneuser_index'   => $this->translator->trans('phoneuser.title', [], 'gestion.breadcrumb'),
            'gestion_phoneuser_edit'    => $this->translator->trans('phoneuser.edit', [], 'gestion.breadcrumb'),
            'gestion_phoneuser_new'     => $this->translator->trans('phoneuser.new', [], 'gestion.breadcrumb'),
            'gestion_phoneuser_trash'   => $this->translator->trans('phoneuser.trash', [], 'gestion.breadcrumb'),
            'gestion_phoneuser_preview' => $this->translator->trans('phoneuser.preview', [], 'gestion.breadcrumb'),
            'gestion_phoneuser_show'    => $this->translator->trans('phoneuser.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return PhoneUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_phoneuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_phoneuser_index',
            'new'      => 'gestion_phoneuser_new',
            'preview'  => 'gestion_phoneuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_phoneuser_show',
            'trash'    => 'gestion_phoneuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
