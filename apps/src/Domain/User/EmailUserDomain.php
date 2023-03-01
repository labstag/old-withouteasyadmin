<?php

namespace Labstag\Domain\User;

use Labstag\Entity\EmailUser;

use Labstag\Form\Admin\Search\User\EmailUserType as SearchEmailUserType;
use Labstag\Form\Admin\User\EmailUserType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\EmailUserRepository;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Search\User\EmailUserSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailUserDomain extends DomainLib
{
    public function __construct(
        protected EmailUserRequestHandler $emailUserRequestHandler,
        protected EmailUserRepository $emailUserRepository,
        protected EmailUserSearch $emailUserSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return EmailUser::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->emailUserRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->emailUserRequestHandler;
    }

    public function getSearchData(): EmailUserSearch
    {
        return $this->emailUserSearch;
    }

    public function getSearchForm(): string
    {
        return SearchEmailUserType::class;
    }

    /**
     * @return mixed[]
     */
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
