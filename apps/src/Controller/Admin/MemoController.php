<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Form\Admin\MemoType;
use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MemoRepository;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\Search\MemoSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/memo")
 */
class MemoController extends AdminControllerLib
{
    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_memo_edit",
     *  methods={"GET","POST"}
     * )
     * @Route("/new", name="admin_memo_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Memo $noteInterne,
        MemoRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            MemoType::class,
            !is_null($noteInterne) ? $noteInterne : new Memo(),
            $noteInterne,
            'admin/memo/form.html.twig'
        );
    }

    /**
     * @Route("/trash", name="admin_memo_trash", methods={"GET"})
     * @Route("/", name="admin_memo_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(MemoRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/memo/index.html.twig',
        );
    }

    /**
     * @Route("/{id}", name="admin_memo_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_memo_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Memo $noteInterne
    ): Response
    {
        return $this->renderShowOrPreview(
            $noteInterne,
            'admin/memo/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_memo_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_memo_index',
            'new'      => 'admin_memo_new',
            'preview'  => 'admin_memo_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_memo_show',
            'trash'    => 'admin_memo_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchMemoType::class,
            'data' => new MemoSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterne(): array
    {
        return [
            [
                'title'        => $this->translator->trans('memo.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('memo.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('memo.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinternePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('memo.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('memo.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_memo_trash',
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
                'admin_memo' => $this->translator->trans('memo.title', [], 'admin.header'),
            ]
        );
    }
}
