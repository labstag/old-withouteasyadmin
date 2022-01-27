<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Form\Admin\MemoType;
use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\Search\MemoSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/memo')]
class MemoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_memo_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_memo_new', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Memo $noteInterne, MemoRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            MemoType::class,
            !is_null($noteInterne) ? $noteInterne : new Memo(),
            'admin/memo/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_memo_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_memo_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Memo::class,
            'admin/memo/index.html.twig',
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_memo_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_memo_preview', methods: ['GET'])]
    public function showOrPreview(Memo $noteInterne): Response
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
                'title' => $this->translator->trans('memo.title', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('memo.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('memo.new', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinternePreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_trash',
            ],
            [
                'title' => $this->translator->trans('memo.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('memo.show', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_memo_trash',
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
