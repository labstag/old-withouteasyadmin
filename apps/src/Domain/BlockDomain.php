<?php

namespace Labstag\Domain;

use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\BlockRepository;
use Labstag\RequestHandler\BlockRequestHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlockDomain extends DomainLib
{
    public function __construct(
        protected BlockRequestHandler $blockRequestHandler,
        protected BlockRepository $blockRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Block::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->blockRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->blockRequestHandler;
    }

    /**
     * @return mixed[]
     */
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
