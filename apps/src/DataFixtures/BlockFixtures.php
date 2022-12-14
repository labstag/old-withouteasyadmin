<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Block;
use Labstag\Lib\FixtureLib;

class BlockFixtures extends FixtureLib implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            MenuFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $json = $this->installService->getData('data/block');
        foreach ($json as $data) {
            $this->addBlocks($data['region'], $data['blocks']);
        }
    }

    protected function addBlock(
        string $region,
        int $position,
        array $blockData
    ): void
    {
        $type  = $blockData['type'];
        $block = new Block();
        $old   = clone $block;
        $block->setTitle($region.' - '.$type.'('.($position + 1).')');
        $block->setRegion($region);
        $block->setType($type);
        $block->setPosition($position + 1);
        if (array_key_exists('code-menu', $blockData)) {
            $menu        = $this->getReference('menu_'.$blockData['code-menu']);
            $classentity = $this->blockService->getTypeEntity($block);
            $entity      = $this->blockService->getEntity($block);
            if (!is_null($entity) || is_null($classentity)) {
                return;
            }

            $entity = new $classentity();
            $entity->setBlock($block);
            $entity->setMenu($menu);
            $block->addMenu($entity);
        }

        $this->blockRequestHandler->handle($old, $block);

        $this->addReference('block_'.$region.'-'.$type, $block);
    }

    protected function addBlocks($region, $blocks)
    {
        foreach ($blocks as $position => $block) {
            $this->addBlock($region, $position, $block);
        }
    }
}
