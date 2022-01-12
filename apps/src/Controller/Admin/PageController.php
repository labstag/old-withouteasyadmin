<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Page;
use Labstag\Form\Admin\PageType;
use Labstag\Form\Admin\Search\PageType as SearchPageType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\Search\PageSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/page")
 */
class PageController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_page_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_page_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Page $page,
        PageRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            PageType::class,
            !is_null($page) ? $page : new Page(),
            'admin/page/form.html.twig'
        );
    }

    /**
     * @Route("/trash", name="admin_page_trash", methods={"GET"})
     * @Route("/", name="admin_page_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(
        EntityManagerInterface $entityManager,
        PageRepository $repository
    ): Response
    {
        return $this->listOrTrash(
            $entityManager,
            $repository,
            'admin/page/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_page_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_page_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Page $page
    ): Response
    {
        return $this->renderShowOrPreview(
            $page,
            'admin/page/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_page_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_page_index',
            'new'      => 'admin_page_new',
            'preview'  => 'admin_page_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_page_show',
            'trash'    => 'admin_page_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchPageType::class,
            'data' => new PageSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminPage(): array
    {
        return [
            [
                'title'        => $this->translator->trans('page.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPageEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('page.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPageNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('page.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPagePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('page.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('page.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPageShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('page.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminPageTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('page.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_page_trash',
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
                'admin_bookmark' => $this->translator->trans('page.title', [], 'admin.header'),
            ]
        );
    }
}
