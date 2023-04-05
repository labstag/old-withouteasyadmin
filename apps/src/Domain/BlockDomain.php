<?php

namespace Labstag\Domain;

use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\BlockSearch;

class BlockDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Block::class;
    }

    public function getSearchData(): BlockSearch
    {
        return new BlockSearch();
    }

    public function getTemplates(): array
    {
        return [
            'index'  => 'admin/block/index.html.twig',
            'trash'  => 'admin/block/index.html.twig',
            'edit'   => 'admin/block/form.html.twig',
            'move'   => 'admin/block/move.html.twig',
            'import' => 'admin/block/import.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'admin_block_index' => $this->translator->trans('block.title', [], 'admin.breadcrumb'),
            'admin_block_edit'  => $this->translator->trans('block.edit', [], 'admin.breadcrumb'),
            'admin_block_move'  => $this->translator->trans('block.move', [], 'admin.breadcrumb'),
            'admin_block_trash' => $this->translator->trans('block.trash', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return BlockType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'move'    => 'admin_block_move',
            'add'     => 'admin_block_new',
            'edit'    => 'admin_block_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_block_index',
            'restore' => 'api_action_restore',
            'trash'   => 'admin_block_trash',
        ];
    }
}
