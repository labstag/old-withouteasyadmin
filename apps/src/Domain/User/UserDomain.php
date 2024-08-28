<?php

namespace Labstag\Domain\User;

use Labstag\Entity\User;
use Labstag\Form\Gestion\Search\UserType as SearchUserType;
use Labstag\Form\Gestion\User\UserType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\UserSearch;

class UserDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return User::class;
    }

    public function getSearchData(): UserSearch
    {
        return new UserSearch();
    }

    public function getSearchForm(): string
    {
        return SearchUserType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/user/index.html.twig',
            'trash'   => 'gestion/user/index.html.twig',
            'edit'    => 'gestion/user/form.html.twig',
            'new'     => 'gestion/user/form.html.twig',
            'guard'   => 'gestion/guard/user.html.twig',
            'show'    => 'gestion/user/show.html.twig',
            'preview' => 'gestion/user/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_user_index'   => $this->translator->trans('user.title', [], 'gestion.breadcrumb'),
            'gestion_user_edit'    => $this->translator->trans('user.edit', [], 'gestion.breadcrumb'),
            'gestion_user_guard'   => $this->translator->trans('user.guard', [], 'gestion.breadcrumb'),
            'gestion_user_new'     => $this->translator->trans('user.new', [], 'gestion.breadcrumb'),
            'gestion_user_trash'   => $this->translator->trans('user.trash', [], 'gestion.breadcrumb'),
            'gestion_user_preview' => $this->translator->trans('user.preview', [], 'gestion.breadcrumb'),
            'gestion_user_show'    => $this->translator->trans('user.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return UserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_user_edit',
            'empty'    => 'api_action_empty',
            'guard'    => 'gestion_user_guard',
            'list'     => 'gestion_user_index',
            'new'      => 'gestion_user_new',
            'preview'  => 'gestion_user_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_user_show',
            'trash'    => 'gestion_user_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
