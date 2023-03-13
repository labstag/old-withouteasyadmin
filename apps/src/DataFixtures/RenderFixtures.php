<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Entity\Render;
use Labstag\Lib\FixtureLib;

class RenderFixtures extends FixtureLib implements DependentFixtureInterface
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
        $routes = $this->guardService->getPublicRouteWithParams();
        foreach (array_keys($routes) as $route) {
            $words  = explode('_', $route);
            $words  = array_map(static fn ($value) => ucfirst(strtolower((string) $value)), $words);
            $words  = implode(' ', $words);
            $render = new Render();
            $old    = clone $render;
            $render->setUrl($route);
            $render->setName($words);
            $this->renderRequestHandler->handle($old, $render);
        }
    }
}
