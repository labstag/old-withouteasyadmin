<?php

namespace Labstag\Domain\User;

use Labstag\Entity\Groupe;

use Labstag\Form\Admin\Search\GroupeType as SearchGroupeType;
use Labstag\Form\Admin\User\GroupeType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\GroupeRepository;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\Search\GroupeSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class GroupeDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected GroupeRequestHandler $groupeRequestHandler,
        protected GroupeRepository $groupeRepository,
        protected GroupeSearch $groupeSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Groupe::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->groupeRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->groupeRequestHandler;
    }

    public function getSearchData(): GroupeSearch
    {
        return $this->groupeSearch;
    }

    public function getSearchForm(): string
    {
        return SearchGroupeType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_groupuser_index'   => $this->translator->trans('groupuser.title', [], 'admin.breadcrumb'),
            'admin_groupuser_edit'    => $this->translator->trans('groupuser.edit', [], 'admin.breadcrumb'),
            'admin_groupuser_guard'   => $this->translator->trans('groupuser.guard', [], 'admin.breadcrumb'),
            'admin_groupuser_new'     => $this->translator->trans('groupuser.new', [], 'admin.breadcrumb'),
            'admin_groupuser_trash'   => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
            'admin_groupuser_preview' => $this->translator->trans('groupuser.preview', [], 'admin.breadcrumb'),
            'admin_groupuser_show'    => $this->translator->trans('groupuser.show', [], 'admin.breadcrumb'),
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
            'edit'    => 'admin_groupuser_edit',
            'empty'   => 'api_action_empty',
            'guard'   => 'admin_groupuser_guard',
            'list'    => 'admin_groupuser_index',
            'new'     => 'admin_groupuser_new',
            'preview' => 'admin_groupuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_groupuser_show',
            'trash'   => 'admin_groupuser_trash',
        ];
    }
}
