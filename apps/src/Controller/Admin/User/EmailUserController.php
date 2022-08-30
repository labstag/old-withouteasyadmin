<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\Search\User\EmailUserType as UserEmailUserType;
use Labstag\Form\Admin\User\EmailUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Search\User\EmailUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/email')]
class EmailUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_emailuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_emailuser_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $service,
        ?EmailUser $emailUser,
        EmailUserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            EmailUserType::class,
            !is_null($emailUser) ? $emailUser : new EmailUser()
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_emailuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_emailuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            EmailUser::class,
            'admin/user/email_user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_emailuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_emailuser_preview', methods: ['GET'])]
    public function showOrPreview(EmailUser $emailUser): Response
    {
        return $this->renderShowOrPreview(
            $emailUser,
            'admin/user/email_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_emailuser_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_emailuser_index',
            'new'      => 'admin_emailuser_new',
            'preview'  => 'admin_emailuser_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_emailuser_show',
            'trash'    => 'admin_emailuser_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => UserEmailUserType::class,
            'data' => new EmailUserSearch(),
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
                    'title' => $this->translator->trans('emailuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_index',
                ],
                [
                    'title' => $this->translator->trans('emailuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_edit',
                ],
                [
                    'title' => $this->translator->trans('emailuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_new',
                ],
                [
                    'title' => $this->translator->trans('emailuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_trash',
                ],
                [
                    'title' => $this->translator->trans('emailuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_preview',
                ],
                [
                    'title' => $this->translator->trans('emailuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_emailuser_show',
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
                'admin_emailuser' => $this->translator->trans('emailuser.title', [], 'admin.header'),
            ]
        );
    }
}
