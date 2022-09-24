<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AddressUser;
use Labstag\Form\Admin\Search\User\AddressUserType as UserAddressUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\Search\User\AddressUserSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/adresse')]
class AddressUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_addressuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_addressuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?AddressUser $addressUser,
        AddressUserRequestHandler $addressUserRequestHandler
    ): Response
    {
        return $this->form(
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

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(AddressUser::class);
    }

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

        return [
            ...$headers, ...
            [
                'admin_addressuser' => $this->translator->trans('addressuser.title', [], 'admin.header'),
            ],
        ];
    }
}
