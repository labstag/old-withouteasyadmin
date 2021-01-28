<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\PhoneUserType;
use Labstag\Repository\PhoneUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\PhoneUserRequestHandler;

/**
 * @Route("/admin/user/phone")
 */
class PhoneUserController extends AdminControllerLib
{

    protected string $headerTitle = "Téléphone d'utilisateurs";

    protected string $urlHome = 'admin_phoneuser_index';

    /**
     * @Route("/trash", name="admin_phoneuser_trash", methods={"GET"})
     * @Route("/", name="admin_phoneuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(PhoneUserRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/phone_user/index.html.twig',
            [
                'new'   => 'admin_phoneuser_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_phoneuser_trash',
                'list'  => 'admin_phoneuser_index',
            ],
            [
                'list'     => 'admin_phoneuser_index',
                'show'     => 'admin_phoneuser_show',
                'preview'  => 'admin_phoneuser_preview',
                'edit'     => 'admin_phoneuser_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'admin_phoneuser_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(PhoneUserRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new PhoneUser(),
            PhoneUserType::class,
            $requestHandler,
            ['list' => 'admin_phoneuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_phoneuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_phoneuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->showOrPreview(
            $phoneUser,
            'admin/phone_user/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'list'    => 'admin_phoneuser_index',
                'edit'    => 'admin_phoneuser_edit',
                'trash'   => 'admin_phoneuser_trash',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_phoneuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(PhoneUser $phoneUser, PhoneUserRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            PhoneUserType::class,
            $phoneUser,
            $requestHandler,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_phoneuser_index',
                'show'   => 'admin_phoneuser_show',
            ]
        );
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/workflow/{state}/{id}", name="admin_phoneuser_workflow", methods={"POST"})
     */
    public function workflow(PhoneUser $phoneUser, string $state): Response
    {
        return $this->adminCrudService->workflow($phoneUser, $state);
    }
}
