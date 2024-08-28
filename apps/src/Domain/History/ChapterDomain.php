<?php

namespace Labstag\Domain\History;

use Labstag\Entity\Chapter;

use Labstag\Form\Gestion\ChapterType;
use Labstag\Form\Gestion\Search\ChapterType as SearchChapterType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\ChapterSearch;

class ChapterDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Chapter::class;
    }

    public function getSearchData(): ChapterSearch
    {
        return new ChapterSearch();
    }

    public function getSearchForm(): string
    {
        return SearchChapterType::class;
    }

    public function getTemplates(): array
    {
        return [
            'edit'    => 'gestion/history/chapter/form.html.twig',
            'index'   => 'gestion/history/chapter/index.html.twig',
            'trash'   => 'gestion/history/chapter/index.html.twig',
            'show'    => 'gestion/history/chapter/show.html.twig',
            'preview' => 'gestion/history/chapter/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_chapter_index'   => $this->translator->trans('chapter.title', [], 'gestion.breadcrumb'),
            'gestion_chapter_edit'    => $this->translator->trans('chapter.edit', [], 'gestion.breadcrumb'),
            'gestion_chapter_new'     => $this->translator->trans('chapter.new', [], 'gestion.breadcrumb'),
            'gestion_chapter_trash'   => $this->translator->trans('chapter.trash', [], 'gestion.breadcrumb'),
            'gestion_chapter_preview' => $this->translator->trans('chapter.preview', [], 'gestion.breadcrumb'),
            'gestion_chapter_show'    => $this->translator->trans('chapter.show', [], 'gestion.breadcrumb'),
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
            'edit'     => 'gestion_chapter_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_chapter_index',
            'preview'  => 'gestion_chapter_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_chapter_show',
            'trash'    => 'gestion_chapter_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
