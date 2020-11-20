<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Repository\EditoRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edito")
 */
class EditoController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_edito_index", methods={"GET"})
     */
    public function index(EditoRepository $editoRepository): Response
    {
        return $this->adminCrudService->list(
            $editoRepository,
            'findAllForAdmin',
            'admin/edito/index.html.twig',
            ['new' => 'admin_edito_new']
        );
    }

    /**
     * @Route("/new", name="admin_edito_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new Edito(),
            EditoType::class,
            ['list' => 'admin_edito_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_show", methods={"GET"})
     */
    public function show(Edito $edito): Response
    {
        return $this->adminCrudService->read(
            $edito,
            'admin/edito/show.html.twig',
            [
                'delete' => 'admin_edito_delete',
                'edit'   => 'admin_edito_edit',
                'list'   => 'admin_edito_index',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_edito_edit", methods={"GET","POST"})
     */
    public function edit(Edito $edito): Response
    {
        return $this->adminCrudService->update(
            EditoType::class,
            $edito,
            [
                'delete' => 'admin_edito_delete',
                'list'   => 'admin_edito_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_delete", methods={"DELETE"})
     */
    public function delete(Edito $edito): Response
    {
        return $this->adminCrudService->delete(
            $edito,
            'admin_edito_index'
        );
    }
}
