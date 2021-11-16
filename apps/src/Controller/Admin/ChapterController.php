<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Form\Admin\ChapterType;
use Labstag\Form\Admin\Search\ChapterType as SearchChapterType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapterRepository;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\Search\ChapterSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/chapter")
 */
class ChapterController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_chapter_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_chapter_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Chapter $chapter,
        ChapterRequestHandler $requestHandler
    ): Response
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
     * @Route("/trash", name="admin_chapter_trash", methods={"GET"})
     * @Route("/", name="admin_chapter_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(ChapterRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/chapter/index.html.twig',
        );
    }

    /**
     * @Route("/{id}", name="admin_chapter_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_chapter_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Chapter $chapter
    ): Response
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
                'title'        => $this->translator->trans('chapter.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('chapter.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('chapter.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('chapter.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('chapter.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminChapterTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_chapter_trash',
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
                'admin_chapter' => $this->translator->trans('chapter.title', [], 'admin.header'),
            ]
        );
    }
}
