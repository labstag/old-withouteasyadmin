<?php

namespace Labstag\Controller\Admin\History;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Form\Admin\ChapterType;
use Labstag\Form\Admin\Search\ChapterType as SearchChapterType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\Search\ChapterSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/chapter')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_chapter_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_chapter_new', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Chapter $chapter, ChapterRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            ChapterType::class,
            !is_null($chapter) ? $chapter : new Chapter(),
            'admin/chapter/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_chapter_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_chapter_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Chapter::class,
            'admin/chapter/index.html.twig',
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_chapter_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_chapter_preview', methods: ['GET'])]
    public function showOrPreview(Chapter $chapter): Response
    {
        return $this->renderShowOrPreview(
            $chapter,
            'admin/chapter/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_chapter_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_chapter_index',
            'new'      => 'admin_chapter_new',
            'preview'  => 'admin_chapter_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_chapter_show',
            'trash'    => 'admin_chapter_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchChapterType::class,
            'data' => new ChapterSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminChapter(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.title', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.new', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterPreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_trash',
            ],
            [
                'title' => $this->translator->trans('chapter.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.show', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_chapter_trash',
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_chapter' => $this->translator->trans('chapter.title', [], 'admin.header'),
            ]
        );
    }
}
