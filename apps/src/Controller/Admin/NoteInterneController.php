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

/**
 * @Route("/admin/noteinterne")
 */
class NoteInterneController extends AdminControllerLib
{

    protected string $headerTitle = 'Note interne';

    protected string $urlHome = 'admin_noteinterne_index';
    /**
     * @Route("/", name="admin_noteinterne_index", methods={"GET"})
     */
    public function index(NoteInterneRepository $repository): Response
    {
        return $this->adminCrudService->list(
            $repository,
            'findAllForAdmin',
            'admin/note_interne/index.html.twig',
            ['new' => 'admin_noteinterne_new'],
            [
                'list'   => 'admin_noteinterne_index',
                'show'   => 'admin_noteinterne_show',
                'edit'   => 'admin_noteinterne_edit',
                'delete' => 'admin_noteinterne_delete',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_noteinterne_new'
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new NoteInterne(),
            NoteInterneType::class,
            ['list' => 'admin_noteinterne_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_show", methods={"GET"})
     */
    public function show(
        NoteInterne $noteInterne,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_noteinterne_show',
                [
                    'id' => $noteInterne->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
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
    public function edit(
        NoteInterne $noteInterne,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_noteinterne_edit',
                [
                    'id' => $noteInterne->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            NoteInterneType::class,
            $noteInterne,
            [
                'delete' => 'admin_noteinterne_delete',
                'list'   => 'admin_noteinterne_index',
                'show'   => 'admin_noteinterne_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_noteinterne_delete", methods={"POST"})
     */
    public function delete(NoteInterne $noteInterne): Response
    {
        return $this->adminCrudService->delete($noteInterne);
    }
}
