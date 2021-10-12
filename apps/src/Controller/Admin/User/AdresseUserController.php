<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AdresseUser;
use Labstag\Form\Admin\User\AdresseUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\AdresseUserRepository;
use Labstag\RequestHandler\AdresseUserRequestHandler;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/adresse")
 */
class AdresseUserController extends AdminControllerLib
{
    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_adresseuser_edit",
     *  methods={"GET","POST"}
     * )
     * @Route("/new", name="admin_adresseuser_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?AdresseUser $adresseUser,
        AdresseUserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            AdresseUserType::class,
            !is_null($adresseUser) ? $adresseUser : new AdresseUser()
        );
    }

    /**
     * @Route("/trash",  name="admin_adresseuser_trash", methods={"GET"})
     * @Route("/",       name="admin_adresseuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(AdresseUserRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/user/adresse_user/index.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_adresseuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_adresseuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        AdresseUser $adresseUser
    ): Response
    {
        return $this->renderShowOrPreview(
            $adresseUser,
            'admin/user/adresse_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_adresseuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_adresseuser_index',
            'new'     => 'admin_adresseuser_new',
            'preview' => 'admin_adresseuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_adresseuser_show',
            'trash'   => 'admin_adresseuser_trash',
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuser(): array
    {
        return [
            [
                'title'        => $this->translator->trans('adresseuser.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuserEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('adresseuser.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuserNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('adresseuser.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuserPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('adresseuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('adresseuser.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuserShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('adresseuser.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminAdresseuserTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('adresseuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_adresseuser_trash',
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
                'admin_adresseuser' => $this->translator->trans('adresseuser.title', [], 'admin.header'),
            ]
        );
    }
}
