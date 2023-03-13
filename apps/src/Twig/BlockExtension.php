<?php

namespace Labstag\Twig;

use Labstag\Entity\Block;
use Labstag\Interfaces\BlockInterface;
use Labstag\Lib\ExtensionLib;

class BlockExtension extends ExtensionLib
{
    public function getBlockClass(BlockInterface $entityBlockLib): string
    {
        /** @var Block $block */
        $block = $entityBlockLib->getBlock();

        return 'block-'.$block->getType();
    }

    public function getBlockId(BlockInterface $entityBlockLib): string
    {
        /** @var Block $block */
        $block = $entityBlockLib->getBlock();

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
