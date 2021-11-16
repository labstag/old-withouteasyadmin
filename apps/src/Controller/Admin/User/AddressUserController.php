<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AddressUser;
use Labstag\Form\Admin\Search\User\AddressUserType as UserAddressUserType;
use Labstag\Form\Admin\User\AddressUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\AddressUserRepository;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\Search\User\AddressUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/adresse")
 */
class AddressUserController extends AdminControllerLib
{
    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_addressuser_edit",
     *  methods={"GET","POST"}
     * )
     * @Route("/new", name="admin_addressuser_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?AddressUser $addressUser,
        AddressUserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            AddressUserType::class,
            !is_null($addressUser) ? $addressUser : new AddressUser()
        );
    }

    /**
     * @Route("/trash", name="admin_addressuser_trash", methods={"GET"})
     * @Route("/", name="admin_addressuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(AddressUserRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/user/address_user/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_addressuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_addressuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        AddressUser $addressUser
    ): Response
    {
        return $this->renderShowOrPreview(
            $addressUser,
            'admin/user/address_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_addressuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_addressuser_index',
            'new'     => 'admin_addressuser_new',
            'preview' => 'admin_addressuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_addressuser_show',
            'trash'   => 'admin_addressuser_trash',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => UserAddressUserType::class,
            'data' => new AddressUserSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuser(): array
    {
        return [
            [
                'title'        => $this->translator->trans('addressuser.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuserEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('addressuser.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuserNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('addressuser.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuserPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('addressuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('addressuser.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuserShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('addressuser.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAddressuserTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('addressuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_addressuser_trash',
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
                'admin_addressuser' => $this->translator->trans('addressuser.title', [], 'admin.header'),
            ]
        );
    }
}
