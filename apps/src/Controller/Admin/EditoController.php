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
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\EditoRequestHandler;

/**
 * @Route("/admin/edito")
 */
class EditoController extends AdminControllerLib
{

    protected string $headerTitle = 'Edito';

    protected string $urlHome = 'admin_edito_index';

    /**
     * @Route("/trash", name="admin_edito_trash", methods={"GET"})
     * @Route("/", name="admin_edito_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EditoRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/edito/index.html.twig',
            [
                'new'   => 'admin_edito_new',
                'empty' => 'admin_edito_empty',
                'trash' => 'admin_edito_trash',
                'list'  => 'admin_edito_index',
            ],
            [
                'list'     => 'admin_edito_index',
                'show'     => 'admin_edito_show',
                'preview'  => 'admin_edito_preview',
                'edit'     => 'admin_edito_edit',
                'delete'   => 'admin_edito_delete',
                'destroy'  => 'admin_edito_destroy',
                'restore'  => 'admin_edito_restore',
                'workflow' => 'admin_edito_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_edito_new", methods={"GET","POST"})
     */
    public function new(EditoRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new Edito(),
            EditoType::class,
            $requestHandler,
            ['list' => 'admin_edito_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_edito_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Edito $edito): Response
    {
        return $this->adminCrudService->showOrPreview(
            $edito,
            'admin/edito/show.html.twig',
            [
                'delete'  => 'admin_edito_delete',
                'restore' => 'admin_edito_restore',
                'destroy' => 'admin_edito_destroy',
                'edit'    => 'admin_edito_edit',
                'list'    => 'admin_edito_index',
                'trash'   => 'admin_edito_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_edito_edit", methods={"GET","POST"})
     */
    public function edit(Edito $edito, EditoRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            EditoType::class,
            $edito,
            $requestHandler,
            [
                'delete' => 'admin_edito_delete',
                'list'   => 'admin_edito_index',
                'show'   => 'admin_edito_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_edito_delete", methods={"DELETE"})
     * @Route("/destroy/{id}", name="admin_edito_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_edito_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(Edito $edito): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($edito);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_edito_empty", methods={"DELETE"})
     */
    public function empty(EditoRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/workflow/{state}/{id}", name="admin_edito_workflow", methods={"POST"})
     */
    public function workflow(Edito $edito, string $state): Response
    {
        return $this->adminCrudService->workflow($edito, $state);
    }
}
