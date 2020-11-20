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

/**
 * @Route("/admin/user/adresse")
 */
class AdresseUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_adresseuser_index", methods={"GET"})
     */
    public function index(AdresseUserRepository $repository): Response
    {
        return $this->adminCrudService->list(
            $repository,
            'findAllForAdmin',
            'admin/adresse_user/index.html.twig',
            ['new' => 'admin_adresseuser_new']
        );
    }

    /**
     * @Route("/new", name="admin_adresseuser_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new AdresseUser(),
            AdresseUserType::class,
            ['list' => 'admin_adresseuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_adresseuser_show", methods={"GET"})
     */
    public function show(AdresseUser $adresseUser): Response
    {
        return $this->adminCrudService->read(
            $adresseUser,
            'admin/adresse_user/show.html.twig',
            [
                'delete' => 'admin_adresseuser_delete',
                'edit'   => 'admin_adresseuser_edit',
                'list'   => 'admin_adresseuser_index',
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
    public function edit(AdresseUser $adresseUser): Response
    {
        return $this->adminCrudService->update(
            AdresseUserType::class,
            $adresseUser,
            [
                'delete' => 'admin_adresseuser_delete',
                'list'   => 'admin_adresseuser_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_adresseuser_delete", methods={"DELETE"})
     */
    public function delete(AdresseUser $adresseUser): Response
    {
        return $this->adminCrudService->delete(
            $adresseUser,
            'admin_adresseuser_index'
        );
    }
}
