<?php

namespace Labstag\Controller\Admin\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\LinkUser;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/lien', name: 'admin_linkuser_')]
class LinkUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
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
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/link_user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
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
