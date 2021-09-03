<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\User\EmailUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\EmailUserRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\GuardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/email")
 */
class EmailUserController extends AdminControllerLib
{

    protected string $headerTitle = 'Email utilisateurs';

    protected string $urlHome = 'admin_emailuser_index';

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_emailuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        GuardService $guarService,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        EmailUser $emailUser,
        EmailUserRequestHandler $requestHandler
    ): Response
    {
        return $this->update(
            $uploadAnnotReader,
            $guarService,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            EmailUserType::class,
            $emailUser,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_emailuser_index',
                'show'   => 'admin_emailuser_show',
            ]
        );
    }

    /**
     * @Route("/trash", name="admin_emailuser_trash", methods={"GET"})
     * @Route("/", name="admin_emailuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EmailUserRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/user/email_user/index.html.twig',
            [
                'new'   => 'admin_emailuser_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_emailuser_trash',
                'list'  => 'admin_emailuser_index',
            ],
            [
                'list'     => 'admin_emailuser_index',
                'show'     => 'admin_emailuser_show',
                'preview'  => 'admin_emailuser_preview',
                'edit'     => 'admin_emailuser_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_emailuser_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        EmailUserRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new EmailUser(),
            EmailUserType::class,
            ['list' => 'admin_emailuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_emailuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_emailuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(EmailUser $emailUser): Response
    {
        return $this->renderShowOrPreview(
            $emailUser,
            'admin/user/email_user/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_emailuser_edit',
                'list'    => 'admin_emailuser_index',
                'trash'   => 'admin_emailuser_trash',
            ]
        );
    }
}
