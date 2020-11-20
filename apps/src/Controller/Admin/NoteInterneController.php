<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\NoteInterne;
use Labstag\Form\Admin\NoteInterneType;
use Labstag\Repository\NoteInterneRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/noteinterne")
 */
class NoteInterneController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_noteinterne_index", methods={"GET"})
     */
    public function index(NoteInterneRepository $repository): Response
    {
        return $this->adminCrudService->list(
            $repository,
            'findAllForAdmin',
            'admin/note_interne/index.html.twig',
            ['new' => 'admin_noteinterne_new']
        );
    }

    /**
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new NoteInterne(),
            NoteInterneType::class,
            ['list' => 'admin_noteinterne_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_show", methods={"GET"})
     */
    public function show(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->read(
            $noteInterne,
            'admin/note_interne/show.html.twig',
            [
                'delete' => 'admin_noteinterne_delete',
                'list'   => 'admin_noteinterne_index',
                'edit'   => 'admin_noteinterne_edit',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_noteinterne_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->update(
            NoteInterneType::class,
            $noteInterne,
            [
                'delete' => 'admin_noteinterne_delete',
                'list'   => 'admin_noteinterne_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_delete", methods={"DELETE"})
     */
    public function delete(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->delete(
            $noteInterne,
            'admin_noteinterne_index'
        );
    }
}
