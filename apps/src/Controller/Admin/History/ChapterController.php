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
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history/chapter')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_chapter_edit', methods: ['GET', 'POST'])]
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

    #[Route(path: '/new/{id}', name: 'admin_chapter_new', methods: ['GET', 'POST'])]
    public function new(
        History $history,
        ChapterRepository $repository,
        ChapterRequestHandler $requestHandler
    ): Response
    {
        $chapter = new Chapter();
        $chapter->setRefhistory($history);
        $chapter->setName(Uuid::v1());
        $chapter->setPosition(count($history->getChapters()) + 1);
        $old = clone $chapter;
        $repository->add($chapter);
        $requestHandler->handle($old, $chapter);

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

    protected function searchForm(): array
    {
        return [
            'form' => SearchChapterType::class,
            'data' => new ChapterSearch(),
        ];
    }

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
