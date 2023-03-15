<?php

namespace Labstag\Domain\History;

use Labstag\Entity\History;

use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Search\HistorySearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class HistoryDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected HistoryRepository $historyRepository,
        protected HistorySearch $historySearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return History::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->historyRepository;
    }

    public function getSearchData(): HistorySearch
    {
        return $this->historySearch;
    }

    public function getSearchForm(): string
    {
        return SearchHistoryType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_history_edit'    => $this->translator->trans('history.edit', [], 'admin.breadcrumb'),
            'admin_history_move'    => $this->translator->trans('history.move', [], 'admin.breadcrumb'),
            'admin_history_new'     => $this->translator->trans('history.new', [], 'admin.breadcrumb'),
            'admin_history_trash'   => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
            'admin_history_preview' => $this->translator->trans('history.preview', [], 'admin.breadcrumb'),
            'admin_history_show'    => $this->translator->trans('history.show', [], 'admin.breadcrumb'),
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
            'move'     => 'admin_history_move',
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
}
