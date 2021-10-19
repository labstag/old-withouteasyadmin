<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\Search\User\PhoneUserType as UserPhoneUserType;
use Labstag\Form\Admin\User\PhoneUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PhoneUserRepository;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\Search\User\PhoneUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/phone")
 */
class PhoneUserController extends AdminControllerLib
{
    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_phoneuser_edit",
     *  methods={"GET","POST"}
     * )
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?PhoneUser $phoneUser,
        PhoneUserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            PhoneUserType::class,
            !is_null($phoneUser) ? $phoneUser : new PhoneUser()
        );
    }

    /**
     * @Route("/trash",  name="admin_phoneuser_trash", methods={"GET"})
     * @Route("/",       name="admin_phoneuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(PhoneUserRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/user/phone_user/index.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_phoneuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_phoneuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        PhoneUser $phoneUser
    ): Response
    {
        return $this->renderShowOrPreview(
            $phoneUser,
            'admin/user/phone_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_phoneuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_phoneuser_index',
            'new'      => 'admin_phoneuser_new',
            'preview'  => 'admin_phoneuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_phoneuser_show',
            'trash'    => 'admin_phoneuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => UserPhoneUserType::class,
            'data' => new PhoneUserSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuser(): array
    {
        return [
            [
                'title'        => $this->translator->trans('phoneuser.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuserEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('phoneuser.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuserNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('phoneuser.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuserPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('phoneuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('phoneuser.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuserShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('phoneuser.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPhoneuserTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('phoneuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_phoneuser_trash',
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
                'admin_phoneuser' => $this->translator->trans('phoneuser.title', [], 'admin.header'),
            ]
        );
    }
}
