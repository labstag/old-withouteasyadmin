<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AddressUser;
use Labstag\Form\Admin\Search\User\AddressUserType as UserAddressUserType;
use Labstag\Form\Admin\User\AddressUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\Search\User\AddressUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/adresse')]
class AddressUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_addressuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_addressuser_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        ?AddressUser $addressUser,
        AddressUserRequestHandler $addressUserRequestHandler
    ): Response
    {
        return $this->form(
            $attachFormService,
            $addressUserRequestHandler,
            AddressUserType::class,
            is_null($addressUser) ? new AddressUser() : $addressUser
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_addressuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_addressuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            AddressUser::class,
            'admin/user/address_user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_addressuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_addressuser_preview', methods: ['GET'])]
    public function showOrPreview(AddressUser $addressUser): Response
    {
        return $this->renderShowOrPreview(
            $addressUser,
            'admin/user/address_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
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

    /**
     * @return array<string, class-string<\Labstag\Form\Admin\Search\User\AddressUserType>>|array<string, \AddressUserSearch>
     */
    protected function searchForm(): array
    {
        return [
            'form' => UserAddressUserType::class,
            'data' => new AddressUserSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_index',
                ],
                [
                    'title' => $this->translator->trans('addressuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_index',
                ],
                [
                    'title' => $this->translator->trans('addressuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_edit',
                ],
                [
                    'title' => $this->translator->trans('addressuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_new',
                ],
                [
                    'title' => $this->translator->trans('addressuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_trash',
                ],
                [
                    'title' => $this->translator->trans('addressuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_preview',
                ],
                [
                    'title' => $this->translator->trans('addressuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_addressuser_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_addressuser' => $this->translator->trans('addressuser.title', [], 'admin.header'),
            ]
        );
    }
}
