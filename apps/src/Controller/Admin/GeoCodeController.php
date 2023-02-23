<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\GeoCode;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/geocode')]
class GeoCodeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_geocode_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_geocode_new', methods: ['GET', 'POST'])]
    public function edit(
        ?GeoCode $geoCode
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($geoCode) ? new GeoCode() : $geoCode
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_geocode_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_geocode_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/geocode/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_geocode_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_geocode_preview', methods: ['GET'])]
    public function showOrPreview(GeoCode $geoCode): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $geoCode,
            'admin/geocode/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(GeoCode::class);
    }
}
