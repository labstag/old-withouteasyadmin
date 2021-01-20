<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\GeoCode;
use Labstag\Form\Admin\GeoCodeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GeoCodeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\RequestHandler\GeoCodeRequestHandler;

/**
 * @Route("/admin/geocode")
 */
class GeoCodeController extends AdminControllerLib
{

    protected string $headerTitle = 'Gecode';

    protected string $urlHome = 'admin_geocode_index';

    /**
     * @Route("/trash", name="admin_geocode_trash", methods={"GET"})
     * @Route("/", name="admin_geocode_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function index(GeoCodeRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/geocode/index.html.twig',
            [
                'new'   => 'admin_geocode_new',
                'empty' => 'admin_geocode_empty',
                'trash' => 'admin_geocode_trash',
                'list'  => 'admin_geocode_index',
            ],
            [
                'list'        => 'admin_geocode_index',
                'show'        => 'admin_geocode_show',
                'edit'        => 'admin_geocode_edit',
                'delete'      => 'admin_geocode_delete',
                'trashdelete' => 'admin_geocode_destroy',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_geocode_new", methods={"GET","POST"})
     */
    public function new(GeoCodeRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->create(
            new GeoCode(),
            GeoCodeType::class,
            $requestHandler,
            ['list' => 'admin_geocode_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_geocode_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_geocode_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(GeoCode $geoCode): Response
    {
        return $this->adminCrudService->showOrPreview(
            $geoCode,
            'admin/geocode/show.html.twig',
            [
                'delete'  => 'admin_geocode_delete',
                'restore' => 'admin_geocode_restore',
                'destroy' => 'admin_geocode_destroy',
                'list'    => 'admin_geocode_index',
                'edit'    => 'admin_geocode_edit',
                'trash'   => 'admin_geocode_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_geocode_edit", methods={"GET","POST"})
     */
    public function edit(GeoCode $geoCode, GeoCodeRequestHandler $requestHandler): Response
    {
        return $this->adminCrudService->update(
            GeoCodeType::class,
            $geoCode,
            $requestHandler,
            [
                'delete' => 'admin_geocode_delete',
                'list'   => 'admin_geocode_index',
                'show'   => 'admin_geocode_show',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_geocode_delete", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_geocode_restore")
     * @Route("/destroy/{id}", name="admin_geocode_destroy", methods={"DELETE"})
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(GeoCode $geoCode): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($geoCode);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_geocode_empty", methods={"DELETE"})
     */
    public function empty(GeoCodeRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
