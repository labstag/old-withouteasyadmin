<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\GroupeType;
use Labstag\Repository\GroupeRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;

/**
 * @Route("/admin/user/groupe")
 */
class GroupeController extends AdminControllerLib
{

    protected string $headerTitle = "Groupe d'utilisateurs";

    protected string $urlHome = 'admin_groupuser_index';

    /**
     * @Route("/trash", name="admin_groupuser_trash", methods={"GET"})
     * @Route("/", name="admin_groupuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function index(GroupeRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/groupe/index.html.twig',
            [
                'new'   => 'admin_groupuser_new',
                'empty' => 'admin_groupuser_empty',
                'trash' => 'admin_groupuser_trash',
                'list'  => 'admin_groupuser_index',
            ],
            [
                'list'        => 'admin_groupuser_index',
                'show'        => 'admin_groupuser_show',
                'edit'        => 'admin_groupuser_edit',
                'delete'      => 'admin_groupuser_delete',
                'trashdelete' => 'admin_groupuser_destroy',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_groupuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_groupuser_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new Groupe(),
            GroupeType::class,
            ['list' => 'admin_groupuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_groupuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_groupuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Groupe $groupe, RouterInterface $router): Response
    {
        return $this->adminCrudService->showOrPreview(
            $groupe,
            'admin/groupe/show.html.twig',
            [
                'delete'  => 'admin_groupuser_delete',
                'restore' => 'admin_groupuser_restore',
                'destroy' => 'admin_groupuser_destroy',
                'edit'    => 'admin_groupuser_edit',
                'list'    => 'admin_groupuser_index',
                'trash'   => 'admin_groupuser_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_groupuser_edit", methods={"GET","POST"})
     */
    public function edit(Groupe $groupe, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_groupuser_edit',
                [
                    'id' => $groupe->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            GroupeType::class,
            $groupe,
            [
                'delete' => 'admin_groupuser_delete',
                'list'   => 'admin_groupuser_index',
                'show'   => 'admin_groupuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_groupuser_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_groupuser_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_groupuser_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(Groupe $groupe): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($groupe);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_groupuser_empty", methods={"DELETE"})
     */
    public function empty(GroupeRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
