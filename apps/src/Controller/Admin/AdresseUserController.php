<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\AdresseUser;
use Labstag\Form\Admin\AdresseUserType;
use Labstag\Repository\AdresseUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;

/**
 * @Route("/admin/user/adresse")
 */
class AdresseUserController extends AdminControllerLib
{

    protected string $headerTitle = 'Adresse utilisateurs';

    protected string $urlHome = 'admin_adresseuser_index';

    /**
     * @Route("/trash", name="admin_adresseuser_trash", methods={"GET"})
     * @Route("/", name="admin_adresseuser_index", methods={"GET"})
     */
    public function indexOrTrash(AdresseUserRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/adresse_user/index.html.twig',
            [
                'new'   => 'admin_adresseuser_new',
                'empty' => 'admin_adresseuser_empty',
                'trash' => 'admin_adresseuser_trash',
                'list'  => 'admin_adresseuser_index',
            ],
            [
                'list'    => 'admin_adresseuser_index',
                'show'    => 'admin_adresseuser_show',
                'preview' => 'admin_adresseuser_preview',
                'edit'    => 'admin_adresseuser_edit',
                'delete'  => 'admin_adresseuser_delete',
                'destroy' => 'admin_adresseuser_destroy',
                'restore' => 'admin_adresseuser_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_adresseuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'new' => $router->generate('admin_adresseuser_new'),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new AdresseUser(),
            AdresseUserType::class,
            ['list' => 'admin_adresseuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_adresseuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_adresseuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        AdresseUser $adresseUser,
        RouterInterface $router
    ): Response
    {
        return $this->adminCrudService->showOrPreview(
            $adresseUser,
            'admin/adresse_user/show.html.twig',
            [
                'delete'  => 'admin_adresseuser_delete',
                'restore' => 'admin_adresseuser_restore',
                'destroy' => 'admin_adresseuser_destroy',
                'edit'    => 'admin_adresseuser_edit',
                'list'    => 'admin_adresseuser_index',
                'trash'   => 'admin_adresseuser_trash',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_adresseuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(
        AdresseUser $adresseUser,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'edit' => $router->generate(
                'admin_adresseuser_edit',
                [
                    'id' => $adresseUser->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            AdresseUserType::class,
            $adresseUser,
            [
                'delete' => 'admin_adresseuser_delete',
                'list'   => 'admin_adresseuser_index',
                'show'   => 'admin_adresseuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_adresseuser_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_adresseuser_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_adresseuser_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(AdresseUser $adresseUser): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($adresseUser);
    }

    /**
     * @Route("/empty", name="admin_adresseuser_empty", methods={"DELETE"})
     * @IgnoreSoftDelete
     */
    public function empty(AdresseUserRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
