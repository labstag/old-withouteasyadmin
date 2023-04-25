<?php

namespace Labstag\Twig;

use Labstag\Entity\Block;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Lib\ExtensionLib;

class BlockExtension extends ExtensionLib
{
    public function getBlockClass(EntityBlockInterface $entityBlock): string
    {
        /** @var Block $block */
        $block = $entityBlock->getBlock();

        return 'block-'.$block->getType();
    }

    public function getBlockId(EntityBlockInterface $entityBlock): string
    {
        /** @var Block $block */
        $block = $entityBlock->getBlock();

        return 'block-'.$block->getType().'-'.$block->getId();
    }

    public function getFiltersFunctions(): array
    {
        return [
            'block_id'    => 'getBlockId',
            'block_class' => 'getBlockClass',
        ];
    }
}
