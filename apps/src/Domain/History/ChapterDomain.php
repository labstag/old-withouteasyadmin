<?php

namespace Labstag\Domain\History;

use Labstag\Entity\Chapter;

use Labstag\Form\Admin\ChapterType;
use Labstag\Form\Admin\Search\ChapterType as SearchChapterType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\ChapterRepository;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\Search\ChapterSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChapterDomain extends DomainLib
{
    public function __construct(
        protected ChapterRequestHandler $chapterRequestHandler,
        protected ChapterRepository $chapterRepository,
        protected ChapterSearch $chapterSearch,
        TranslatorInterface $translator
    ) {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Chapter::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->chapterRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->chapterRequestHandler;
    }

    public function getSearchData(): ChapterSearch
    {
        return $this->chapterSearch;
    }

    public function getSearchForm(): string
    {
        return SearchChapterType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_chapter_index'   => $this->translator->trans('chapter.title', [], 'admin.breadcrumb'),
            'admin_chapter_edit'    => $this->translator->trans('chapter.edit', [], 'admin.breadcrumb'),
            'admin_chapter_new'     => $this->translator->trans('chapter.new', [], 'admin.breadcrumb'),
            'admin_chapter_trash'   => $this->translator->trans('chapter.trash', [], 'admin.breadcrumb'),
            'admin_chapter_preview' => $this->translator->trans('chapter.preview', [], 'admin.breadcrumb'),
            'admin_chapter_show'    => $this->translator->trans('chapter.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return ChapterType::class;
    }

    public function getUrlAdmin(): array
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
}
