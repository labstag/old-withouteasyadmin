<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\Search\User\PhoneUserType as UserPhoneUserType;
use Labstag\Form\Admin\User\PhoneUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\Search\User\PhoneUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/phone')]
class PhoneUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_phoneuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_phoneuser_new', methods: ['GET', 'POST'])]
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
            is_null($phoneUser) ? new PhoneUser() : $phoneUser
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_phoneuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_phoneuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            PhoneUser::class,
            'admin/user/phone_user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_phoneuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_phoneuser_preview', methods: ['GET'])]
    public function showOrPreview(PhoneUser $phoneUser): Response
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

    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_index',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_index',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_edit',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_new',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_trash',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_preview',
                ],
                [
                    'title' => $this->translator->trans('phoneuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_phoneuser_show',
                ],
            ]
        );
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
