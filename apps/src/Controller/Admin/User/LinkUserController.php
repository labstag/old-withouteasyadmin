<?php

namespace Labstag\Controller\Admin\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\LinkUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/lien')]
class LinkUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_linkuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_linkuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?LinkUser $linkUser
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($linkUser) ? new LinkUser() : $linkUser
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_linkuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_linkuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/link_user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_linkuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_linkuser_preview', methods: ['GET'])]
    public function showOrPreview(LinkUser $linkUser): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $linkUser,
            'admin/user/link_user/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(LinkUser::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
