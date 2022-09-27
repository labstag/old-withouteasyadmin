<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\GeoCode;
use Labstag\Form\Admin\Search\GeoCodeType as SearchGeoCodeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Search\GeoCodeSearch;
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

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(GeoCode::class);
    }

    /**
     * @return array<string, \GeoCodeSearch>|array<string, class-string<\Labstag\Form\Admin\Search\GeoCodeType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchGeoCodeType::class,
            'data' => new GeoCodeSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('geocode.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_index',
                ],
                [
                    'title' => $this->translator->trans('geocode.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_edit',
                ],
                [
                    'title' => $this->translator->trans('geocode.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_new',
                ],
                [
                    'title' => $this->translator->trans('geocode.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_trash',
                ],
                [
                    'title' => $this->translator->trans('geocode.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_preview',
                ],
                [
                    'title' => $this->translator->trans('geocode.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_geocode_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [
            ...$headers, ...
            [
                'admin_geocode' => $this->translator->trans('geocode.title', [], 'admin.header'),
            ],
        ];
    }
}
