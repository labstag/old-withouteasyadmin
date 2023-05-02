<?php

namespace Labstag\Twig;

use Labstag\Entity\Block;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Lib\ExtensionLib;
use Twig\TwigFilter;
use Twig\TwigFunction;

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

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('block_id', [$this, 'getBlockId']),
            new TwigFilter('block_class', [$this, 'getBlockClass']),
        ];
    }
}
