<?php

namespace Labstag\Controller\Admin\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\EmailUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/email', name: 'admin_emailuser_')]
class EmailUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
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
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/email_user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
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
        $domainLib = $this->domainService->getDomain(EmailUser::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
