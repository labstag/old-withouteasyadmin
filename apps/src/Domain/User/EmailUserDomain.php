<?php

namespace Labstag\Domain\User;

use Labstag\Entity\EmailUser;

use Labstag\Form\Gestion\Search\User\EmailUserType as SearchEmailUserType;
use Labstag\Form\Gestion\User\EmailUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\User\EmailUserSearch;

class EmailUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return EmailUser::class;
    }

    public function getSearchData(): EmailUserSearch
    {
        return new EmailUserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchEmailUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/email/index.html.twig',
            'trash'   => 'gestion/user/email/index.html.twig',
            'show'    => 'gestion/user/email/show.html.twig',
            'preview' => 'gestion/user/email/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_emailuser_index'   => $this->translator->trans('emailuser.title', [], 'gestion.breadcrumb'),
            'gestion_emailuser_edit'    => $this->translator->trans('emailuser.edit', [], 'gestion.breadcrumb'),
            'gestion_emailuser_new'     => $this->translator->trans('emailuser.new', [], 'gestion.breadcrumb'),
            'gestion_emailuser_trash'   => $this->translator->trans('emailuser.trash', [], 'gestion.breadcrumb'),
            'gestion_emailuser_preview' => $this->translator->trans('emailuser.preview', [], 'gestion.breadcrumb'),
            'gestion_emailuser_show'    => $this->translator->trans('emailuser.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return EmailUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_emailuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_emailuser_index',
            'new'      => 'gestion_emailuser_new',
            'preview'  => 'gestion_emailuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_emailuser_show',
            'trash'    => 'gestion_emailuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
