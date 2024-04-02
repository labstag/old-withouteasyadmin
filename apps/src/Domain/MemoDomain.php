<?php

namespace Labstag\Domain;

use Labstag\Entity\Memo;
use Labstag\Form\Gestion\MemoType;

use Labstag\Form\Gestion\Search\MemoType as SearchMemoType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\MemoSearch;

class MemoDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Memo::class;
    }

    public function getSearchData(): MemoSearch
    {
        return new MemoSearch();
    }

    public function getSearchForm(): string
    {
        return SearchMemoType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/memo/index.html.twig',
            'trash'   => 'gestion/memo/index.html.twig',
            'show'    => 'gestion/memo/show.html.twig',
            'preview' => 'gestion/memo/show.html.twig',
            'edit'    => 'gestion/memo/form.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_memo_index'   => $this->translator->trans('memo.title', [], 'gestion.breadcrumb'),
            'gestion_memo_edit'    => $this->translator->trans('memo.edit', [], 'gestion.breadcrumb'),
            'gestion_memo_new'     => $this->translator->trans('memo.new', [], 'gestion.breadcrumb'),
            'gestion_memo_trash'   => $this->translator->trans('memo.trash', [], 'gestion.breadcrumb'),
            'gestion_memo_preview' => $this->translator->trans('memo.preview', [], 'gestion.breadcrumb'),
            'gestion_memo_show'    => $this->translator->trans('memo.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return MemoType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_memo_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_memo_index',
            'new'      => 'gestion_memo_new',
            'preview'  => 'gestion_memo_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_memo_show',
            'trash'    => 'gestion_memo_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
