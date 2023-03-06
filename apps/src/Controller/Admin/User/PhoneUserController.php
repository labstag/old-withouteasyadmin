<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\PhoneUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/phone')]
class PhoneUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_phoneuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_phoneuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?PhoneUser $phoneUser
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($phoneUser) ? new PhoneUser() : $phoneUser
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_phoneuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_phoneuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/phone_user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_phoneuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_phoneuser_preview', methods: ['GET'])]
    public function showOrPreview(PhoneUser $phoneUser): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $phoneUser,
            'admin/user/phone_user/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(PhoneUser::class);
    }
}
