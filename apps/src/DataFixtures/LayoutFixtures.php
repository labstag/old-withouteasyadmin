<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Block;
use Labstag\Entity\Layout;
use Labstag\Lib\FixtureLib;

class LayoutFixtures extends FixtureLib implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            BlockFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $json = $this->installService->getData('data/layout');
        foreach ($json as $data) {
            $this->addLayouts($data);
        }
    }

    protected function addLayout(
        string $type,
        string $region,
        array $dataLayout
    ): void {
        /** @var Block $block */
        $block   = $this->getReference('block_'.$region.'-'.$type);
        $layout  = new Layout();
        $old     = clone $layout;
        $customs = $block->getCustoms();
        $layout->setCustom($customs[0]);
        $layout->setName($dataLayout['name']);
        $layout->setUrl($dataLayout['url']);

        $this->layoutRequestHandler->handle($old, $layout);
        if (isset($dataLayout['paragraphs'])) {
            $this->addParagraphs($layout, $dataLayout['paragraphs']);
        }

        $this->addReference('layout_'.$type.'-'.$region.'-'.$dataLayout['name'], $layout);
    }

    protected function addLayouts(array $data): void
    {
        $type   = $data['block-type'];
        $region = $data['block-region'];
        foreach ($data['layouts'] as $layout) {
            $this->addLayout($type, $region, $layout);
        }
    }
}
