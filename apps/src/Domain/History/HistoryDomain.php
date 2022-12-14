<?php

namespace Labstag\Domain\History;

use Labstag\Entity\History;

use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\HistoryRepository;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Search\HistorySearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class HistoryDomain extends DomainLib
{
    public function __construct(
        protected HistoryRequestHandler $historyRequestHandler,
        protected HistoryRepository $historyRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return History::class;
    }

    public function getRepository()
    {
        return $this->historyRepository;
    }

    public function getRequestHandler()
    {
        return $this->historyRequestHandler;
    }

    public function getSearchData()
    {
        return new HistorySearch();
    }

    public function getSearchForm()
    {
        return SearchHistoryType::class;
    }

    /**
     * @return mixed[]
     */
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

    public function getType()
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
