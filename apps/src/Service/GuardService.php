<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Entity\RouteGroupe;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Symfony\Component\Routing\Route as Routing;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Registry;

class GuardService
{
    public const GROUPE_ENABLE = ['visiteur'];

    public const REGEX = [
        '/(SecurityController)/',
        '/(web_profiler.controller)/',
        '/(error_controller)/',
        '/(api_platform)/',
    ];

    public const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    public function __construct(
        protected RouterInterface $router,
        protected EntityManagerInterface $entityManager,
        protected Registry $workflows
    )
    {
    }

    public function all(): array
    {
        $data       = [];
        $collection = $this->router->getRouteCollection();
        $all        = $collection->all();
        foreach ($all as $name => $route) {
            // @var Routing $route
            $defaults = $route->getDefaults();
            if (!isset($defaults['_controller'])) {
                continue;
            }

            $matches = $this->regex($defaults['_controller']);
            if (0 != (is_countable($matches) ? count($matches) : 0)) {
                continue;
            }

            $data[$name] = $route;
        }

        return $data;
    }

    public function delete(): void
    {
        $all    = $this->all();
        $routes = [];
        foreach (array_keys($all) as $name) {
            $routes[] = $name;
        }

        $results = $this->getRepository(Route::class)->findLost($routes);
        foreach ($results as $route) {
            $this->entityManager->remove($route);
        }

        $this->entityManager->flush();
    }

    public function getGuardRoutesForGroupe(Groupe $groupe): array
    {
        $routes = $this->routesEnableGroupe($groupe);
        if ('superadmin' == $groupe->getCode()) {
            $routes = [];
        }

        return $routes;
    }

    public function getGuardRoutesForUser(User $user): array
    {
        $routes = $this->routesEnableUser($user);
        if ('superadmin' == $user->getRefgroupe()->getCode()) {
            $routes = [];
        }

        return $routes;
    }

    public function guardRoute($route, $token)
    {
        $all = $this->all();
        if (!array_key_exists($route, $all)) {
            return true;
        }

        if (empty($token) || !$token->getUser() instanceof User) {
            $groupe = $this->getRepository(Groupe::class)->findOneBy(['code' => 'visiteur']);

            return !(!$this->searchRouteGroupe($groupe, $route));
        }

        // @var User $user
        $user   = $token->getUser();
        $groupe = $user->getRefgroupe();
        if ('superadmin' == $groupe->getCode()) {
            return true;
        }

        $state = $this->searchRouteUser($user, $route);

        return !(!$state);
    }

    public function guardRouteEnableGroupe(string $route, Groupe $groupe): bool
    {
        return $this->isRouteGroupe(
            $groupe,
            $route
        );
    }

    public function guardRouteEnableUser(string $route, User $user): bool
    {
        return $this->isRouteGroupe(
            $user->getRefgroupe(),
            $route
        );
    }

    public function old()
    {
        $all    = $this->all();
        $routes = [];
        foreach (array_keys($all) as $name) {
            $routes[] = $name;
        }

        $results = $this->getRepository(Route::class)->findLost($routes);
        $data    = [];
        foreach ($results as $route) {
            $data[] = [$route];
        }

        return $data;
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

    public function routesEnableGroupe(Groupe $groupe): array
    {
        $data   = $this->getRepository(Route::class)->findBy([], ['name' => 'ASC']);
        $routes = [];
        foreach ($data as $route) {
            $state = $this->guardRouteEnableGroupe($route, $groupe);
            if (!$state) {
                continue;
            }

            $routes[] = $route;
        }

        return $routes;
    }

    public function routesEnableUser(User $user): array
    {
        $data   = $this->getRepository(Route::class)->findBy([], ['name' => 'ASC']);
        $routes = [];
        foreach ($data as $route) {
            $state = $this->guardRouteEnableUser($route, $user);
            if (!$state) {
                continue;
            }

            $routes[] = $route;
        }

        return $routes;
    }

    public function save($name): void
    {
        $search = ['name' => $name];
        $result = $this->getRepository(Route::class)->findOneBy(
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

    public function tables()
    {
        $data = [];
        $all  = $this->all();
        foreach ($all as $name => $route) {
            // @var Routing $route
            $defaults = $route->getDefaults();
            $data[]   = [
                $name,
                $defaults['_controller'],
            ];
        }

        return $data;
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function searchRouteGroupe(Groupe $groupe, string $route): bool
    {
        $entity = $this->getRepository(RouteGroupe::class)->findRoute($groupe, $route);
        if (empty($entity)) {
            return false;
        }

        return $entity->isState();
    }

    protected function searchRouteUser(User $user, string $route): bool
    {
        $stateGroupe = $this->searchRouteGroupe($user->getRefgroupe(), $route);
        $entity      = $this->getRepository(RouteUser::class)->findRoute($user, $route);
        $stateUser   = ($entity instanceof RouteUser) ? $entity->isState() : false;

        return $stateGroupe || $stateUser;
    }

    private function isRouteGroupe(
        $groupe,
        $route
    )
    {
        $all = $this->all();
        if ('superadmin' == $groupe->getCode()) {
            return true;
        }

        if (!array_key_exists($route, $all)) {
            return false;
        }

        $data     = $all[$route];
        $defaults = $data->getDefaults();
        $matches  = [];
        preg_match(self::REGEX_CONTROLLER_ADMIN, $defaults['_controller'], $matches);

        return !(0 != count($matches) && 'visiteur' == $groupe->getCode());
    }
}
