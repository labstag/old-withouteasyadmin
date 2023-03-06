<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\EmailUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/email')]
class EmailUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_emailuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_emailuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?EmailUser $emailUser
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($emailUser) ? new EmailUser() : $emailUser
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_emailuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_emailuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/email_user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_emailuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_emailuser_preview', methods: ['GET'])]
    public function showOrPreview(EmailUser $emailUser): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $emailUser,
            'admin/user/email_user/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(EmailUser::class);
    }
}
