<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\LienUser;
use Labstag\Form\Admin\LienUserType;
use Labstag\Repository\LienUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;

/**
 * @Route("/admin/user/lien")
 */
class LienUserController extends AdminControllerLib
{

    protected string $headerTitle = 'Lien utilisateurs';

    protected string $urlHome = 'admin_lienuser_index';

    /**
     * @Route("/trash", name="admin_lienuser_trash", methods={"GET"})
     * @Route("/", name="admin_lienuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(LienUserRepository $lienUserRepository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $lienUserRepository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/lien_user/index.html.twig',
            [
                'new'   => 'admin_lienuser_new',
                'empty' => 'admin_lienuser_empty',
                'trash' => 'admin_lienuser_trash',
                'list'  => 'admin_lienuser_index',
            ],
            [
                'list'    => 'admin_lienuser_index',
                'show'    => 'admin_lienuser_show',
                'preview' => 'admin_lienuser_preview',
                'edit'    => 'admin_lienuser_edit',
                'delete'  => 'admin_lienuser_delete',
                'destroy' => 'admin_lienuser_destroy',
                'restore' => 'admin_lienuser_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_lienuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_lienuser_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new LienUser(),
            LienUserType::class,
            ['list' => 'admin_lienuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_lienuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_lienuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(LienUser $lienUser, RouterInterface $router): Response
    {
        return $this->adminCrudService->showOrPreview(
            $lienUser,
            'admin/lien_user/show.html.twig',
            [
                'delete'  => 'admin_lienuser_delete',
                'restore' => 'admin_lienuser_restore',
                'destroy' => 'admin_lienuser_destroy',
                'list'    => 'admin_lienuser_index',
                'edit'    => 'admin_lienuser_edit',
                'trash'   => 'admin_lienuser_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_lienuser_edit", methods={"GET","POST"})
     */
    public function edit(LienUser $lienUser, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_lienuser_edit',
                [
                    'id' => $lienUser->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            LienUserType::class,
            $lienUser,
            [
                'delete' => 'admin_lienuser_delete',
                'list'   => 'admin_lienuser_index',
                'show'   => 'admin_lienuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_lienuser_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_lienuser_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_lienuser_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(LienUser $lienUser): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($lienUser);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_lienuser_empty", methods={"DELETE"})
     */
    public function empty(LienUserRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
