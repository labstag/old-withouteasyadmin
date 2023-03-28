<?php

namespace Labstag\Domain\User;

use Labstag\Entity\EmailUser;

use Labstag\Form\Admin\Search\User\EmailUserType as SearchEmailUserType;
use Labstag\Form\Admin\User\EmailUserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\User\EmailUserSearch;

class EmailUserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return EmailUser::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->emailUserRepository;
    }

    public function getSearchData(): EmailUserSearch
    {
        return $this->emailUserSearch;
    }

    public function getSearchForm(): string
    {
        return SearchEmailUserType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_emailuser_index'   => $this->translator->trans('emailuser.title', [], 'admin.breadcrumb'),
            'admin_emailuser_edit'    => $this->translator->trans('emailuser.edit', [], 'admin.breadcrumb'),
            'admin_emailuser_new'     => $this->translator->trans('emailuser.new', [], 'admin.breadcrumb'),
            'admin_emailuser_trash'   => $this->translator->trans('emailuser.trash', [], 'admin.breadcrumb'),
            'admin_emailuser_preview' => $this->translator->trans('emailuser.preview', [], 'admin.breadcrumb'),
            'admin_emailuser_show'    => $this->translator->trans('emailuser.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_emailuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_emailuser_index',
            'new'      => 'admin_emailuser_new',
            'preview'  => 'admin_emailuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_emailuser_show',
            'trash'    => 'admin_emailuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
