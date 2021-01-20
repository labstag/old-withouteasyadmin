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
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\NoteInterneRequestHandler;

/**
 * @Route("/admin/noteinterne")
 */
class NoteInterneController extends AdminControllerLib
{

    protected string $headerTitle = 'Note interne';

    protected string $urlHome = 'admin_noteinterne_index';

    /**
     * @Route("/trash", name="admin_noteinterne_trash", methods={"GET"})
     * @Route("/", name="admin_noteinterne_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(NoteInterneRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/note_interne/index.html.twig',
            [
                'new'   => 'admin_noteinterne_new',
                'empty' => 'admin_noteinterne_empty',
                'trash' => 'admin_noteinterne_trash',
                'list'  => 'admin_noteinterne_index',
            ],
            [
                'list'    => 'admin_noteinterne_index',
                'show'    => 'admin_noteinterne_show',
                'preview' => 'admin_noteinterne_preview',
                'edit'    => 'admin_noteinterne_edit',
                'delete'  => 'admin_noteinterne_delete',
                'destroy' => 'admin_noteinterne_destroy',
                'restore' => 'admin_noteinterne_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function new(NoteInterneRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new NoteInterne(),
            NoteInterneType::class,
            $requestHandler,
            ['list' => 'admin_noteinterne_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_noteinterne_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->showOrPreview(
            $noteInterne,
            'admin/note_interne/show.html.twig',
            [
                'delete'  => 'admin_noteinterne_delete',
                'restore' => 'admin_noteinterne_restore',
                'destroy' => 'admin_noteinterne_destroy',
                'list'    => 'admin_noteinterne_index',
                'edit'    => 'admin_noteinterne_edit',
                'trash'   => 'admin_noteinterne_trash',
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
    public function edit(NoteInterne $noteInterne, NoteInterneRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            NoteInterneType::class,
            $noteInterne,
            $requestHandler,
            [
                'delete' => 'admin_noteinterne_delete',
                'list'   => 'admin_noteinterne_index',
                'show'   => 'admin_noteinterne_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_noteinterne_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_noteinterne_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_noteinterne_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($noteInterne);
    }

    /**
     * @Route("/empty", name="admin_noteinterne_empty", methods={"DELETE"})
     */
    public function empty(NoteInterneRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
