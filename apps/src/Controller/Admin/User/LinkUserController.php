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

/**
 * @Route("/admin/user/lien")
 */
class LinkUserController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_linkuser_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_linkuser_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?LinkUser $linkUser,
        LinkUserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            LinkUserType::class,
            !is_null($linkUser) ? $linkUser : new LinkUser()
        );
    }

    /**
     * @Route("/trash", name="admin_linkuser_trash", methods={"GET"})
     * @Route("/", name="admin_linkuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            LinkUser::class,
            'admin/user/link_user/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_linkuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_linkuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        LinkUser $linkUser
    ): Response
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

    protected function searchForm(): array
    {
        return [
            'form' => UserLinkUserType::class,
            'data' => new LinkUserSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuser(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.title', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuserEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuserNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.new', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuserPreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_trash',
            ],
            [
                'title' => $this->translator->trans('linkuser.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuserShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.show', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminLinkuserTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('linkuser.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_linkuser_trash',
            ],
        ];
    }

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
