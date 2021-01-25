<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Route;
use Labstag\Repository\RouteRepository;
use Symfony\Component\Routing\RouterInterface;

class GuardRouteService
{

    private RouterInterface $router;

    private RouteRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        RouteRepository $repository
    )
    {
        $this->entityManager = $entityManager;
        $this->repository    = $repository;
        $this->router        = $router;
    }

    public function all(): array
    {
        $data       = [];
        $collection = $this->router->getRouteCollection();
        $all        = $collection->all();
        foreach ($all as $name => $route) {
            /** @var Route $route */
            $defaults = $route->getDefaults();
            if (!isset($defaults['_controller'])) {
                continue;
            }

            if (0 == substr_count($defaults['_controller'], 'Labstag\Controller')) {
                continue;
            }

            $data[$name] = $route;
        }

        return $data;
    }

    public function tables()
    {
        $data       = [];
        $collection = $this->router->getRouteCollection();
        $all        = $collection->all();
        foreach ($all as $name => $route) {
            /** @var Route $route */
            $defaults = $route->getDefaults();
            if (!isset($defaults['_controller'])) {
                continue;
            }

            if (0 == substr_count($defaults['_controller'], 'Labstag\Controller')) {
                continue;
            }

            $data[] = [
                $name,
                $defaults['_controller'],
            ];
        }

        return $data;
    }

    public function save($name): void
    {
        $search = ['name' => $name];
        $result = $this->repository->findOneBy(
            $search
        );

        if (!is_null($result)) {
            return;
        }

        $route = new Route();
        $route->setName($name);

        $this->entityManager->persist($route);
        $this->entityManager->flush();
    }

    public function delete(array $routes): void
    {
        $results = $this->repository->findLost($routes);
        foreach ($results as $route) {
            $this->entityManager->remove($route);
        }

        $this->entityManager->flush();
    }

    public function old(array $routes)
    {
        $results = $this->repository->findLost($routes);
        $data    = [];
        foreach ($results as $route) {
            $data[] = [$route];
        }

        return $data;
    }
}
