<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\EmailUserType;
use Labstag\Repository\EmailUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\EmailUserRequestHandler;

/**
 * @Route("/admin/user/email")
 */
class EmailUserController extends AdminControllerLib
{

    protected string $headerTitle = 'Email utilisateurs';

    protected string $urlHome = 'admin_emailuser_index';

    /**
     * @Route("/trash", name="admin_emailuser_trash", methods={"GET"})
     * @Route("/", name="admin_emailuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EmailUserRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/email_user/index.html.twig',
            [
                'new'   => 'admin_emailuser_new',
                'empty' => 'admin_emailuser_empty',
                'trash' => 'admin_emailuser_trash',
                'list'  => 'admin_emailuser_index',
            ],
            [
                'list'     => 'admin_emailuser_index',
                'show'     => 'admin_emailuser_show',
                'preview'  => 'admin_emailuser_preview',
                'edit'     => 'admin_emailuser_edit',
                'delete'   => 'admin_emailuser_delete',
                'destroy'  => 'admin_emailuser_destroy',
                'restore'  => 'admin_emailuser_restore',
                'workflow' => 'admin_emailuser_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_emailuser_new", methods={"GET","POST"})
     */
    public function new(EmailUserRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new EmailUser(),
            EmailUserType::class,
            $requestHandler,
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
        return $this->adminCrudService->showOrPreview(
            $emailUser,
            'admin/email_user/show.html.twig',
            [
                'delete'  => 'admin_emailuser_delete',
                'restore' => 'admin_emailuser_restore',
                'destroy' => 'admin_emailuser_destroy',
                'edit'    => 'admin_emailuser_edit',
                'list'    => 'admin_emailuser_index',
                'trash'   => 'admin_emailuser_trash',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_emailuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(EmailUser $emailUser, EmailUserRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            EmailUserType::class,
            $emailUser,
            $requestHandler,
            [
                'delete' => 'admin_emailuser_delete',
                'list'   => 'admin_emailuser_index',
                'show'   => 'admin_emailuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_emailuser_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_emailuser_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_emailuser_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(EmailUser $emailUser): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($emailUser);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_emailuser_empty", methods={"DELETE"})
     */
    public function empty(EmailUserRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/workflow/{state}/{id}", name="admin_emailuser_workflow", methods={"POST"})
     */
    public function workflow(EmailUser $emailUser, string $state): Response
    {
        return $this->adminCrudService->workflow($emailUser, $state);
    }
}
