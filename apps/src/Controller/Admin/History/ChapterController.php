<?php

namespace Labstag\Controller\Admin\History;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Form\Admin\ChapterType;
use Labstag\Form\Admin\Search\ChapterType as SearchChapterType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapterRepository;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\Search\ChapterSearch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history/chapter')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_chapter_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Chapter $chapter,
        ChapterRequestHandler $chapterRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $chapterRequestHandler,
            ChapterType::class,
            is_null($chapter) ? new Chapter() : $chapter,
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

    #[Route(path: '/new/{id}', name: 'admin_chapter_new', methods: ['GET', 'POST'])]
    public function new(
        History $history,
        ChapterRepository $chapterRepository,
        ChapterRequestHandler $chapterRequestHandler
    ): RedirectResponse
    {
        $chapter = new Chapter();
        $chapter->setRefhistory($history);
        $chapter->setName(Uuid::v1());
        $chapter->setPosition((is_countable($history->getChapters()) ? count($history->getChapters()) : 0) + 1);

        $old = clone $chapter;
        $chapterRepository->add($chapter);
        $chapterRequestHandler->handle($old, $chapter);

        return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapter->getId()]);
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
            'preview'  => 'admin_chapter_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_chapter_show',
            'trash'    => 'admin_chapter_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    /**
     * @return array<string, \ChapterSearch>|array<string, class-string<\Labstag\Form\Admin\Search\ChapterType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchChapterType::class,
            'data' => new ChapterSearch(),
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
                    'title' => $this->translator->trans('history.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_index',
                ],
                [
                    'title' => $this->translator->trans('chapter.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_index',
                ],
                [
                    'title' => $this->translator->trans('chapter.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_edit',
                ],
                [
                    'title' => $this->translator->trans('chapter.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_new',
                ],
                [
                    'title' => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_trash',
                ],
                [
                    'title' => $this->translator->trans('chapter.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_preview',
                ],
                [
                    'title' => $this->translator->trans('chapter.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_chapter_show',
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

        return [
            ...$headers, ...
            [
                'admin_chapter' => $this->translator->trans('chapter.title', [], 'admin.header'),
            ],
        ];
    }
}
