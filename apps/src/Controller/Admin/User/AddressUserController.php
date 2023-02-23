<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AddressUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/adresse')]
class AddressUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_addressuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_addressuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?AddressUser $addressUser
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
            $addressUser,
            'admin/user/address_user/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(AddressUser::class);
    }
}
