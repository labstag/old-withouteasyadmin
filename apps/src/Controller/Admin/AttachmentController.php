<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/attachment')]
class AttachmentController extends AdminControllerLib
{
    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_attachment_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_attachment_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/attachment/index.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Attachment::class);
    }
}
