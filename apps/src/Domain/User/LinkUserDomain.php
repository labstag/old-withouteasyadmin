<?php

namespace Labstag\Domain\User;

use Labstag\Entity\LinkUser;

use Labstag\Form\Admin\Search\User\LinkUserType as SearchLinkUserType;
use Labstag\Form\Admin\User\LinkUserType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\LinkUserRepository;
use Labstag\RequestHandler\LinkUserRequestHandler;
use Labstag\Search\User\LinkUserSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class LinkUserDomain extends DomainLib
{
    public function __construct(
        protected LinkUserRequestHandler $linkUserRequestHandler,
        protected LinkUserRepository $linkUserRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return LinkUser::class;
    }

    public function getRepository()
    {
        return $this->linkUserRepository;
    }

    public function getRequestHandler()
    {
        return $this->linkUserRequestHandler;
    }

    public function getSearchData()
    {
        return LinkUserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchLinkUserType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_linkuser_index'   => $this->translator->trans('linkuser.title', [], 'admin.breadcrumb'),
            'admin_linkuser_edit'    => $this->translator->trans('linkuser.edit', [], 'admin.breadcrumb'),
            'admin_linkuser_new'     => $this->translator->trans('linkuser.new', [], 'admin.breadcrumb'),
            'admin_linkuser_trash'   => $this->translator->trans('linkuser.trash', [], 'admin.breadcrumb'),
            'admin_linkuser_preview' => $this->translator->trans('linkuser.preview', [], 'admin.breadcrumb'),
            'admin_linkuser_show'    => $this->translator->trans('linkuser.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return LinkUserType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_linkuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_linkuser_index',
            'new'     => 'admin_linkuser_new',
            'preview' => 'admin_linkuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_linkuser_show',
            'trash'   => 'admin_linkuser_trash',
        ];
    }
}
