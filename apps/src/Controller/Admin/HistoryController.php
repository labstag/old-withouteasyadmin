<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\History;
use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Search\HistorySearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/history")
 */
class HistoryController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_history_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_history_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?History $history,
        HistoryRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            HistoryType::class,
            !is_null($history) ? $history : new History(),
            'admin/history/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_history_trash", methods={"GET"})
     * @Route("/",       name="admin_history_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(HistoryRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/history/index.html.twig',
        );
    }

    /**
     * @Route("/{id}",         name="admin_history_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_history_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        History $history
    ): Response
    {
        return $this->renderShowOrPreview(
            $history,
            'admin/history/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_history_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_history_index',
            'new'      => 'admin_history_new',
            'preview'  => 'admin_history_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_history_show',
            'trash'    => 'admin_history_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchHistoryType::class,
            'data' => new HistorySearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminHistory(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('history.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_trash',
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
                'admin_history' => $this->translator->trans('history.title', [], 'admin.header'),
            ]
        );
    }
}
