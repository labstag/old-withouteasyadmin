<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Layout;
use Labstag\Lib\DataFixtureLib;

class LayoutFixtures extends DataFixtureLib implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $this->addLayout('content', $this->installService->getLayoutContent());
        $this->addLayout('home', $this->installService->getLayoutHome());
        $this->addLayout('landing', $this->installService->getLayoutLanding());
    }

    protected function addLayout(string $name, string $content): Layout
    {
        $layout    = new Layout();
        $oldLayout = clone $layout;
        $layout->setName($name);
        $layout->setContent($content);

        $this->addReference('layout_'.$name, $layout);
        $this->layoutRH->handle($oldLayout, $layout);

        return $layout;
    }
}
