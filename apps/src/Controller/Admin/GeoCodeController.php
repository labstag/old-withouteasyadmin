<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\GeoCode;
use Labstag\Lib\AdminControllerLib;
use Labstag\Service\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/geocode', name: 'admin_geocode_')]
class GeoCodeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        GeoCode $geoCode
    ): Response
    {
        return $this->setAdmin()->edit($geoCode);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(GeoCode $geoCode): Response
    {
        return $this->setAdmin()->preview($geoCode);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(GeoCode $geoCode): Response
    {
        return $this->setAdmin()->show($geoCode);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(GeoCode::class);

        return $this->adminService;
    }
}
