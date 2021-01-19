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
     */
    public function indexOrTrash(RouterInterface $router, PhoneUserRepository $repository): Response
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
                'empty' => 'admin_phoneuser_empty',
                'trash' => 'admin_phoneuser_trash',
                'list'  => 'admin_phoneuser_index',
            ],
            [
                'list'    => 'admin_phoneuser_index',
                'show'    => 'admin_phoneuser_show',
                'preview' => 'admin_phoneuser_preview',
                'edit'    => 'admin_phoneuser_edit',
                'delete'  => 'admin_phoneuser_delete',
                'destroy' => 'admin_phoneuser_destroy',
                'restore' => 'admin_phoneuser_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_phoneuser_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new PhoneUser(),
            PhoneUserType::class,
            ['list' => 'admin_phoneuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_phoneuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_phoneuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        PhoneUser $phoneUser,
        RouterInterface $router
    ): Response
    {
        return $this->adminCrudService->showOrPreview(
            $phoneUser,
            'admin/phone_user/show.html.twig',
            [
                'delete'  => 'admin_phoneuser_delete',
                'restore' => 'admin_phoneuser_restore',
                'destroy' => 'admin_phoneuser_destroy',
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
    public function edit(
        PhoneUser $phoneUser,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_phoneuser_edit',
                [
                    'id' => $phoneUser->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            PhoneUserType::class,
            $phoneUser,
            [
                'delete' => 'admin_phoneuser_delete',
                'list'   => 'admin_phoneuser_index',
                'show'   => 'admin_phoneuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_phoneuser_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_phoneuser_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_phoneuser_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($phoneUser);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_phoneuser_empty", methods={"DELETE"})
     */
    public function empty(PhoneUserRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
