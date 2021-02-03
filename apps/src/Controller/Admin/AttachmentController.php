<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Repository\AttachmentRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\EditoRequestHandler;

/**
 * @Route("/admin/attachment")
 */
class AttachmentController extends AdminControllerLib
{

    protected string $headerTitle = 'Attachment';

    protected string $urlHome = 'admin_attachment_index';

    /**
     * @Route("/trash", name="admin_attachment_trash", methods={"GET"})
     * @Route("/", name="admin_attachment_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(AttachmentRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/attachment/index.html.twig',
            [
                'empty' => 'api_action_empty',
                'trash' => 'admin_attachment_trash',
                'list'  => 'admin_attachment_index',
            ],
            [
                'list'     => 'admin_attachment_index',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }
}
