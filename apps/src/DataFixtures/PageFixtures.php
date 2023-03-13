<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Page;
use Labstag\Lib\FixtureLib;

class PageFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $data = $this->installService->getData('data/page');
        foreach ($data as $page) {
            $this->addPage($page);
        }
    }

    protected function addPage(
        array $pageData,
        ?Page $parent = null
    ): void
    {
        $page = new Page();
        $old  = clone $page;
        $page->setName($pageData['name']);
        if (!is_null($parent)) {
            $page->setParent($parent);
        }

        if (isset($pageData['paragraphs'])) {
            $this->addParagraphs($page, $pageData['paragraphs']);
        }

        $reference = 'page_'.$pageData['slug'];
        $this->addReference($reference, $page);
        $this->pageRequestHandler->handle($old, $page);
        if (!array_key_exists('pages', $pageData)) {
            return;
        }

        foreach ($pageData['pages'] as $data) {
            $this->addPage($data, $page);
        }
    }
}
