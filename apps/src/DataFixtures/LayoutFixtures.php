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
        $this->addLayoutLanding();
        $this->addLayoutHome();
        $this->addLayoutContent();
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

    protected function addLayoutContent()
    {
        $content = <<<EOF
            [header]
            [main,aside]
            [footer]
            EOF;
        $this->addLayout('content', $content);
    }

    protected function addLayoutHome()
    {
        $content = <<<EOF
            [header]
            [main]
            [footer]
            EOF;
        $this->addLayout('home', $content);
    }

    protected function addLayoutLanding()
    {
        $content = <<<EOF
            [header]
            [main]
            [footer]
            EOF;
        $this->addLayout('landing', $content);
    }
}
