<?php

namespace Labstag\Domain;

use Labstag\Entity\Block;
use Labstag\Form\Gestion\BlockType;
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
            'index'  => 'gestion/block/index.html.twig',
            'trash'  => 'gestion/block/index.html.twig',
            'edit'   => 'gestion/block/form.html.twig',
            'move'   => 'gestion/block/move.html.twig',
            'import' => 'gestion/block/import.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_block_index' => $this->translator->trans('block.title', [], 'gestion.breadcrumb'),
            'gestion_block_edit'  => $this->translator->trans('block.edit', [], 'gestion.breadcrumb'),
            'gestion_block_move'  => $this->translator->trans('block.move', [], 'gestion.breadcrumb'),
            'gestion_block_trash' => $this->translator->trans('block.trash', [], 'gestion.breadcrumb'),
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
            'move'    => 'gestion_block_move',
            'add'     => 'gestion_block_new',
            'edit'    => 'gestion_block_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'gestion_block_index',
            'restore' => 'api_action_restore',
            'trash'   => 'gestion_block_trash',
        ];
    }
}
