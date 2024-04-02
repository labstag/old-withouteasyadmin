<?php

namespace Labstag\Domain\History;

use Labstag\Entity\History;

use Labstag\Form\Gestion\HistoryType;
use Labstag\Form\Gestion\Search\HistoryType as SearchHistoryType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\HistorySearch;

class HistoryDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return History::class;
    }

    public function getSearchData(): HistorySearch
    {
        return new HistorySearch();
    }

    public function getSearchForm(): string
    {
        return SearchHistoryType::class;
    }

    public function getTemplates(): array
    {
        return [
            'edit'    => 'gestion/history/form.html.twig',
            'move'    => 'gestion/history/move.html.twig',
            'index'   => 'gestion/history/index.html.twig',
            'trash'   => 'gestion/history/index.html.twig',
            'show'    => 'gestion/history/show.html.twig',
            'preview' => 'gestion/history/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_history_edit'    => $this->translator->trans('history.edit', [], 'gestion.breadcrumb'),
            'gestion_history_move'    => $this->translator->trans('history.move', [], 'gestion.breadcrumb'),
            'gestion_history_new'     => $this->translator->trans('history.new', [], 'gestion.breadcrumb'),
            'gestion_history_trash'   => $this->translator->trans('history.trash', [], 'gestion.breadcrumb'),
            'gestion_history_preview' => $this->translator->trans('history.preview', [], 'gestion.breadcrumb'),
            'gestion_history_show'    => $this->translator->trans('history.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return HistoryType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'move'     => 'gestion_history_move',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_history_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_history_index',
            'new'      => 'gestion_history_new',
            'preview'  => 'gestion_history_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_history_show',
            'trash'    => 'gestion_history_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
