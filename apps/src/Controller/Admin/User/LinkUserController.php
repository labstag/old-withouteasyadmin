<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\LinkUser;
use Labstag\Form\Admin\Search\User\LinkUserType as UserLinkUserType;
use Labstag\Form\Admin\User\LinkUserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\LinkUserRequestHandler;
use Labstag\Search\User\LinkUserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/lien')]
class LinkUserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_linkuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_linkuser_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        ?LinkUser $linkUser,
        LinkUserRequestHandler $linkUserRequestHandler
    ): Response
    {
        return $this->form(
            $attachFormService,
            $linkUserRequestHandler,
            LinkUserType::class,
            is_null($linkUser) ? new LinkUser() : $linkUser
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_linkuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_linkuser_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            LinkUser::class,
            'admin/user/link_user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_linkuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_linkuser_preview', methods: ['GET'])]
    public function showOrPreview(LinkUser $linkUser): Response
    {
        return $this->renderShowOrPreview(
            $linkUser,
            'admin/user/link_user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_linkuser_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_linkuser_index',
            'new'     => 'admin_linkuser_new',
            'preview' => 'admin_linkuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_linkuser_show',
            'trash'   => 'admin_linkuser_trash',
        ];
    }

    /**
     * @return array<string, class-string<\Labstag\Form\Admin\Search\User\LinkUserType>>|array<string, \LinkUserSearch>
     */
    protected function searchForm(): array
    {
        return [
            'form' => UserLinkUserType::class,
            'data' => new LinkUserSearch(),
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
                    'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_index',
                ],
                [
                    'title' => $this->translator->trans('linkuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_index',
                ],
                [
                    'title' => $this->translator->trans('linkuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_edit',
                ],
                [
                    'title' => $this->translator->trans('linkuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_new',
                ],
                [
                    'title' => $this->translator->trans('linkuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_trash',
                ],
                [
                    'title' => $this->translator->trans('linkuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_preview',
                ],
                [
                    'title' => $this->translator->trans('linkuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_linkuser_show',
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

        return array_merge(
            $headers,
            [
                'admin_linkuser' => $this->translator->trans('linkuser.title', [], 'admin.header'),
            ]
        );
    }
}
