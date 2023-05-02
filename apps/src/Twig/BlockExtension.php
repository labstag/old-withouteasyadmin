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
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable);
        }

        return $filters;
    }

    public function getFiltersFunctions(): array
    {
        return [
            'block_id'    => 'getBlockId',
            'block_class' => 'getBlockClass',
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable);
        }

        return $functions;
    }
}
