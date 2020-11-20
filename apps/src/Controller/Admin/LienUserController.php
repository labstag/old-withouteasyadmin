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

/**
 * @Route("/admin/user/lien")
 */
class LienUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_lienuser_index", methods={"GET"})
     */
    public function index(LienUserRepository $lienUserRepository): Response
    {
        return $this->adminCrudService->list(
            $lienUserRepository,
            'findAllForAdmin',
            'admin/lien_user/index.html.twig',
            ['new' => 'admin_lienuser_new']
        );
    }

    /**
     * @Route("/new", name="admin_lienuser_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new LienUser(),
            LienUserType::class,
            ['list' => 'admin_lienuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_lienuser_show", methods={"GET"})
     */
    public function show(LienUser $lienUser): Response
    {
        return $this->adminCrudService->read(
            $lienUser,
            'admin/lien_user/show.html.twig',
            [
                'delete' => 'admin_lienuser_delete',
                'list'   => 'admin_lienuser_index',
                'edit'   => 'admin_lienuser_edit',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_lienuser_edit", methods={"GET","POST"})
     */
    public function edit(LienUser $lienUser): Response
    {
        return $this->adminCrudService->update(
            LienUserType::class,
            $lienUser,
            [
                'delete' => 'admin_lienuser_delete',
                'list'   => 'admin_lienuser_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_lienuser_delete", methods={"DELETE"})
     */
    public function delete(LienUser $lienUser): Response
    {
        return $this->adminCrudService->delete(
            $lienUser,
            'admin_lienuser_index'
        );
    }
}
