<?php

namespace Labstag\Domain;

use Labstag\Entity\Memo;
use Labstag\Form\Admin\MemoType;

use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\MemoRepository;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\Search\MemoSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class MemoDomain extends DomainLib
{
    public function __construct(
        protected MemoRequestHandler $memoRequestHandler,
        protected MemoRepository $memoRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Memo::class;
    }

    public function getRepository()
    {
        return $this->memoRepository;
    }

    public function getRequestHandler()
    {
        return $this->memoRequestHandler;
    }

    public function getSearchData()
    {
        return MemoSearch::class;
    }

    public function getSearchForm()
    {
        return SearchMemoType::class;
    }

    /**
     * @return mixed[]
     */
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

    public function getType()
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
