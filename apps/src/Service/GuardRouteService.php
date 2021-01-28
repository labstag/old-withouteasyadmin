<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Entity\User;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\RouteUserRepository;
use Symfony\Component\Routing\Route as Routing;
use Symfony\Component\Routing\RouterInterface;

class GuardRouteService
{

    const GROUPE_ENABLE = ['visiteur'];
    const REGEX         = [
        '/(SecurityController)/',
        '/(web_profiler.controller)/',
        '/(error_controller)/',
        '/(api_platform)/',
    ];

    protected RouterInterface $router;

    protected RouteRepository $repositoryRoute;

    protected EntityManagerInterface $entityManager;

    protected RouteUserRepository $routeUserRepo;

    protected RouteGroupeRepository $routeGroupeRepo;

    public function __construct(
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        RouteUserRepository $routeUserRepo,
        RouteGroupeRepository $routeGroupeRepo,
        RouteRepository $repositoryRoute
    )
    {
        $this->entityManager   = $entityManager;
        $this->routeGroupeRepo = $routeGroupeRepo;
        $this->routeUserRepo   = $routeUserRepo;
        $this->repositoryRoute = $repositoryRoute;
        $this->router          = $router;
    }

    public function regex(string $string)
    {
        $data = [];
        foreach (self::REGEX as $regex) {
            preg_match($regex, $string, $matches);
            foreach ($matches as $info) {
                $data[] = $info;
            }
        }

        return $data;
    }

    public function all(): array
    {
        $data       = [];
        $collection = $this->router->getRouteCollection();
        $all        = $collection->all();
        foreach ($all as $name => $route) {
            /** @var Routing $route */
            $defaults = $route->getDefaults();
            if (!isset($defaults['_controller'])) {
                continue;
            }

            $matches = $this->regex($defaults['_controller']);
            if (0 != count($matches)) {
                continue;
            }

            $data[$name] = $route;
        }

        return $data;
    }

    public function tables()
    {
        $data = [];
        $all  = $this->all();
        foreach ($all as $name => $route) {
            /** @var Routing $route */
            $defaults = $route->getDefaults();
            $data[]   = [
                $name,
                $defaults['_controller'],
            ];
        }

        return $data;
    }

    public function save($name): void
    {
        $search = ['name' => $name];
        $result = $this->repositoryRoute->findOneBy(
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

    public function delete(): void
    {
        $all    = $this->all();
        $routes = [];
        foreach (array_keys($all) as $name) {
            $routes[] = $name;
        }

        $results = $this->repositoryRoute->findLost($routes);
        foreach ($results as $route) {
            $this->entityManager->remove($route);
        }

        $this->entityManager->flush();
    }

    public function old()
    {
        $all    = $this->all();
        $routes = [];
        foreach (array_keys($all) as $name) {
            $routes[] = $name;
        }

        $results = $this->repositoryRoute->findLost($routes);
        $data    = [];
        foreach ($results as $route) {
            $data[] = [$route];
        }

        return $data;
    }

    public function searchRouteGroupe(Groupe $groupe, string $route): bool
    {
        $entity = $this->routeGroupeRepo->findRoute($groupe, $route);
        if (empty($entity)) {
            return false;
        }

        return $entity->isState();
    }

    public function searchRouteUser(User $user, string $route): bool
    {
        $state = $this->searchRouteGroupe($user->getGroupe(), $route);
        if (!$state) {
            return false;
        }

        $entity = $this->routeUserRepo->findRoute($user, $route);
        if (empty($entity)) {
            return false;
        }

        return $entity->isState();
    }
}
