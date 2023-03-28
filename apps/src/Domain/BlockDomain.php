<?php

namespace Labstag\Domain;

use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\BlockSearch;

class BlockDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Block::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->blockRepository;
    }

    public function getSearchData(): BlockSearch
    {
        return $this->blockSearch;
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
            'edit'    => 'admin_block_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_block_index',
            'restore' => 'api_action_restore',
            'trash'   => 'admin_block_trash',
        ];
    }
}
