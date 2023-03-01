<?php

namespace Labstag\Domain\User;

use Labstag\Entity\PhoneUser;

use Labstag\Form\Admin\Search\User\PhoneUserType as SearchPhoneUserType;
use Labstag\Form\Admin\User\PhoneUserType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\PhoneUserRepository;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\Search\User\PhoneUserSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhoneUserDomain extends DomainLib
{
    public function __construct(
        protected PhoneUserRequestHandler $phoneUserRequestHandler,
        protected PhoneUserRepository $phoneUserRepository,
        protected PhoneUserSearch $phoneUserSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return PhoneUser::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->phoneUserRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->phoneUserRequestHandler;
    }

    public function getSearchData(): PhoneUserSearch
    {
        return $this->phoneUserSearch;
    }

    public function getSearchForm(): string
    {
        return SearchPhoneUserType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_phoneuser_index'   => $this->translator->trans('phoneuser.title', [], 'admin.breadcrumb'),
            'admin_phoneuser_edit'    => $this->translator->trans('phoneuser.edit', [], 'admin.breadcrumb'),
            'admin_phoneuser_new'     => $this->translator->trans('phoneuser.new', [], 'admin.breadcrumb'),
            'admin_phoneuser_trash'   => $this->translator->trans('phoneuser.trash', [], 'admin.breadcrumb'),
            'admin_phoneuser_preview' => $this->translator->trans('phoneuser.preview', [], 'admin.breadcrumb'),
            'admin_phoneuser_show'    => $this->translator->trans('phoneuser.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_phoneuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_phoneuser_index',
            'new'      => 'admin_phoneuser_new',
            'preview'  => 'admin_phoneuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_phoneuser_show',
            'trash'    => 'admin_phoneuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
