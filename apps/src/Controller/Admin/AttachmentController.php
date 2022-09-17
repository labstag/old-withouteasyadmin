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
            Attachment::class,
            'admin/attachment/index.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_attachment_index',
            'restore'  => 'api_action_restore',
            'trash'    => 'admin_attachment_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return [
            ...parent::setBreadcrumbsData(), ...
            [
                [
                    'title' => $this->translator->trans('attachment.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_attachment_index',
                ],
            ],
        ];
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
                'admin_attachment' => $this->translator->trans('attachment.title', [], 'admin.header'),
            ],
        ];
    }
}
