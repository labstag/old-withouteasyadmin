<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteGroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\RouteUserRepository;
use Symfony\Component\Routing\Route as Routing;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Workflow\Registry;

class GuardService
{
    /**
     * @var string[]
     */
    final public const GROUPE_ENABLE = ['visiteur'];

    /**
     * @var string[]
     */
    final public const REGEX = [
        '/(SecurityController)/',
        '/(web_profiler.controller)/',
        '/(error_controller)/',
        '/(api_platform)/',
    ];

    /**
     * @var string
     */
    final public const REGEX_CONTROLLER_ADMIN = '/(Controller\\\Admin)/';

    /**
     * @var string[]
     */
    final public const REGEX_PUBLIC = [
        '/(ElfinderBundle)/',
        '/(Admin)/',
        '/(ImagineBundle)/',
        '/(Api)/',
    ];

    public function __construct(
        protected RouterInterface $router,
        protected EntityManagerInterface $entityManager,
        protected Registry $workflows,
        protected GroupeRepository $groupeRepo,
        protected RouteRepository $routeRepo,
        protected RouteGroupeRepository $routeGroupeRepo,
        protected RouteUserRepository $routeUserRepo
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

    public function allRoutes(): array
    {
        $data       = [];
        $collection = $this->router->getRouteCollection();
        $all        = $collection->all();
        foreach ($all as $name => $route) {
            $data[$name] = $route;
        }

        return $data;
    }

    public function delete(): void
    {
        $results = $this->getLostRoute();
        foreach ($results as $route) {
            $this->routeRepo->remove($route);
        }
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

    public function getPublicRoute()
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

            $matches = $this->regexPublic($defaults['_controller']);
            if (0 != (is_countable($matches) ? count($matches) : 0)) {
                continue;
            }

            $data[$name] = $route;
        }

        ksort($data);

        return $data;
    }

    public function getPublicRouteWithParams()
    {
        $data = $this->getPublicRoute();
        if (isset($data['front'])) {
            unset($data['front']);
        }

        foreach ($data as $id => $row) {
            $defaults = $row->getDefaults();
            unset($defaults['_controller']);
            if (0 != (is_countable($defaults) ? count($defaults) : 0)) {
                continue;
            }

            unset($data[$id]);
        }

        return $data;
    }

    public function guardRoute($route, $token)
    {
        $all = $this->all();
        if (!array_key_exists($route, $all)) {
            return true;
        }

        if (empty($token) || !$token->getUser() instanceof User) {
            $groupe = $this->groupeRepo->findOneBy(['code' => 'visiteur']);

            return $this->searchRouteGroupe($groupe, $route);
        }

        // @var User $user
        $user   = $token->getUser();
        $groupe = $user->getRefgroupe();
        if ('superadmin' == $groupe->getCode()) {
            return true;
        }

        return $this->searchRouteUser($user, $route);
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
        $results = $this->getLostRoute();
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
        $data   = $this->routeRepo->findBy([], ['name' => 'ASC']);
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
        $data   = $this->routeRepo->findBy([], ['name' => 'ASC']);
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
        $result = $this->routeRepo->findOneBy(
            $search
        );

        if (!is_null($result)) {
            return;
        }

        $route = new Route();
        $route->setName($name);

        $this->routeRepo->add($route);
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

    protected function searchRouteGroupe(Groupe $groupe, string $route): bool
    {
        $entity = $this->routeGroupeRepo->findRoute($groupe, $route);
        if (empty($entity)) {
            return false;
        }

        return $entity->isState();
    }

    protected function searchRouteUser(User $user, string $route): bool
    {
        $stateGroupe = $this->searchRouteGroupe($user->getRefgroupe(), $route);
        $entity      = $this->routeUserRepo->findRoute($user, $route);
        $stateUser   = ($entity instanceof RouteUser) ? $entity->isState() : false;

        return $stateGroupe || $stateUser;
    }

    private function getLostRoute()
    {
        $all    = $this->all();
        $routes = array_keys($all);

        return $this->routeRepo->findLost($routes);
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
        preg_match(self::REGEX_CONTROLLER_ADMIN, (string) $defaults['_controller'], $matches);

        return !(0 != count($matches) && 'visiteur' == $groupe->getCode());
    }

    private function regexPublic(string $string)
    {
        $data = $this->regex($string);
        foreach (self::REGEX_PUBLIC as $regex) {
            preg_match($regex, $string, $matches);
            foreach ($matches as $info) {
                $data[] = $info;
            }
        }

        return $data;
    }
}
