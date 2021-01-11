<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\GeoCode;
use Labstag\Form\Admin\GeoCodeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GeoCodeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/geocode")
 */
class GeoCodeController extends AdminControllerLib
{

    protected string $headerTitle = 'Gecode';

    protected string $urlHome = 'admin_geocode_index';

    /**
     * @Route("/", name="admin_geocode_index", methods={"GET"})
     */
    public function index(GeoCodeRepository $geoCodeRepository): Response
    {
        return $this->adminCrudService->list(
            $geoCodeRepository,
            'findAllForAdmin',
            'admin/geocode/index.html.twig',
            ['new' => 'admin_template_new'],
            [
                'list'   => 'admin_geocode_index',
                'show'   => 'admin_geocode_show',
                'edit'   => 'admin_geocode_edit',
                'delete' => 'admin_geocode_delete',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_geocode_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_geocode_new'
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new GeoCode(),
            GeoCodeType::class,
            ['list' => 'admin_geocode_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_geocode_show", methods={"GET"})
     */
    public function show(GeoCode $geoCode, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_geocode_show',
                [
                    'id' => $geoCode->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->read(
            $geoCode,
            'admin/geocode/show.html.twig',
            [
                'delete' => 'admin_geocode_delete',
                'list'   => 'admin_geocode_index',
                'edit'   => 'admin_geocode_edit',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_geocode_edit", methods={"GET","POST"})
     */
    public function edit(GeoCode $geoCode, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_template_edit',
                [
                    'id' => $geoCode->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            GeoCodeType::class,
            $geoCode,
            [
                'delete' => 'admin_geocode_delete',
                'list'   => 'admin_geocode_index',
                'show'   => 'admin_geocode_show',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_geocode_delete", methods={"DELETE"})
     */
    public function delete(GeoCode $geoCode): Response
    {
        return $this->adminCrudService->delete($geoCode);
    }
}
