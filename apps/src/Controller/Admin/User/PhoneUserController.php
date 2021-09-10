<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\User\PhoneUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\PhoneUserRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\PhoneUserRequestHandler;
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
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        PhoneUser $phoneUser,
        PhoneUserRequestHandler $requestHandler
    ): Response {
        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            PhoneUserType::class,
            $phoneUser,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_phoneuser_index',
                'show'   => 'admin_phoneuser_show',
            ]
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
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/user/phone_user/index.html.twig',
            [
                'new'   => 'admin_phoneuser_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_phoneuser_trash',
                'list'  => 'admin_phoneuser_index',
            ],
            [
                'list'     => 'admin_phoneuser_index',
                'show'     => 'admin_phoneuser_show',
                'preview'  => 'admin_phoneuser_preview',
                'edit'     => 'admin_phoneuser_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        PhoneUserRequestHandler $requestHandler
    ): Response {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new PhoneUser(),
            PhoneUserType::class,
            ['list' => 'admin_phoneuser_index']
        );
    }

    /**
     * @Route("/{id}",         name="admin_phoneuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_phoneuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        PhoneUser $phoneUser
    ): Response {
        return $this->renderShowOrPreview(
            $phoneUser,
            'admin/user/phone_user/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'list'    => 'admin_phoneuser_index',
                'edit'    => 'admin_phoneuser_edit',
                'trash'   => 'admin_phoneuser_trash',
            ]
        );
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
