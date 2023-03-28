<?php

namespace Labstag\Domain;

use Labstag\Entity\Memo;
use Labstag\Form\Admin\MemoType;

use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\MemoSearch;

class MemoDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Memo::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->memoRepository;
    }

    public function getSearchData(): MemoSearch
    {
        return $this->memoSearch;
    }

    public function getSearchForm(): string
    {
        return SearchMemoType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_memo_index'   => $this->translator->trans('memo.title', [], 'admin.breadcrumb'),
            'admin_memo_edit'    => $this->translator->trans('memo.edit', [], 'admin.breadcrumb'),
            'admin_memo_new'     => $this->translator->trans('memo.new', [], 'admin.breadcrumb'),
            'admin_memo_trash'   => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
            'admin_memo_preview' => $this->translator->trans('memo.preview', [], 'admin.breadcrumb'),
            'admin_memo_show'    => $this->translator->trans('memo.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_memo_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_memo_index',
            'new'      => 'admin_memo_new',
            'preview'  => 'admin_memo_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_memo_show',
            'trash'    => 'admin_memo_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
