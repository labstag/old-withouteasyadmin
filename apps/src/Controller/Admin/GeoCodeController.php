<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\GeoCode;
use Labstag\Form\Admin\GeoCodeType;
use Labstag\Form\Admin\Search\GeocodeType as SearchGeocodeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\GeoCodeRepository;
use Labstag\RequestHandler\GeoCodeRequestHandler;
use Labstag\Search\GeocodeSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/geocode")
 */
class GeoCodeController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_geocode_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_geocode_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?GeoCode $geoCode,
        GeoCodeRequestHandler $requestHandler
    ): Response {
        return $this->form(
            $service,
            $requestHandler,
            GeoCodeType::class,
            !is_null($geoCode) ? $geoCode : new GeoCode()
        );
    }

    /**
     * @Route("/trash", name="admin_geocode_trash", methods={"GET"})
     * @Route("/", name="admin_geocode_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function index(GeoCodeRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/geocode/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_geocode_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_geocode_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        GeoCode $geoCode
    ): Response {
        return $this->renderShowOrPreview(
            $geoCode,
            'admin/geocode/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'      => 'api_action_delete',
            'destroy'     => 'api_action_destroy',
            'edit'        => 'admin_geocode_edit',
            'empty'       => 'api_action_empty',
            'list'        => 'admin_geocode_index',
            'new'         => 'admin_geocode_new',
            'restore'     => 'api_action_restore',
            'show'        => 'admin_geocode_show',
            'trash'       => 'admin_geocode_trash',
            'trashdelete' => 'admin_geocode_destroy',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchGeocodeType::class,
            'data' => new GeocodeSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminGeocode(): array
    {
        return [
            [
                'title'        => $this->translator->trans('geocode.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGeocodeEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('geocode.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGeocodeNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('geocode.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGeocodePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('geocode.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('geocode.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGeocodeShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('geocode.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGeocodeTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('geocode.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_geocode_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_geocode' => $this->translator->trans('geocode.title', [], 'admin.header'),
            ]
        );
    }
}
