<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Block;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\FixtureLib;

class LayoutFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            BlockFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $json = $this->installService->getData('data/layout');
        foreach ($json as $data) {
            $this->addLayouts($data, $objectManager);
        }

        $objectManager->flush();
    }

    protected function addLayout(
        string $type,
        string $region,
        array $dataLayout,
        ObjectManager $objectManager
    ): void {
        /** @var Block $block */
        $block   = $this->getReference('block_'.$region.'-'.$type);
        $layout  = new Layout();
        $customs = $block->getCustoms();
        if (!is_iterable($customs) || !isset($customs[0])) {
            return;
        }

        $custom = $customs[0];
        if (!$custom instanceof Custom) {
            return;
        }

        $layout->setCustom($custom);
        $layout->setName($dataLayout['name']);
        $layout->setUrl($dataLayout['url']);

        $objectManager->persist($layout);
        if (isset($dataLayout['paragraphs'])) {
            $this->addParagraphs($layout, $dataLayout['paragraphs'], $objectManager);
        }

        $this->addReference('layout_'.$type.'-'.$region.'-'.$dataLayout['name'], $layout);
    }

    protected function addLayouts(
        array $data,
        ObjectManager $objectManager
    ): void {
        $type   = $data['block-type'];
        $region = $data['block-region'];
        foreach ($data['layouts'] as $layout) {
            $this->addLayout($type, $region, $layout, $objectManager);
        }
    }
}
