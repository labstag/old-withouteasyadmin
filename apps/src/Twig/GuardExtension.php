<?php

namespace Labstag\Twig;

use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Entity\User;
use Labstag\Lib\ExtensionLib;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GuardExtension extends ExtensionLib
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable);
        }

        return $filters;
    }

    public function getFiltersFunctions(): array
    {
        return [
            'guard_group_access'       => 'guardAccessGroupRoutes',
            'guard_route_enable_group' => 'guardRouteEnableGroupe',
            'guard_route_enable_user'  => 'guardRouteEnableUser',
            'guard_route'              => 'guardRoute',
            'guard_user_access'        => 'guardAccessUserRoutes',
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable);
        }

        return $functions;
    }

    public function guardAccessGroupRoutes(Groupe $groupe): bool
    {
        $routes = $this->guardService->getGuardRoutesForGroupe($groupe);

        return 0 != count($routes);
    }

    public function guardAccessUserRoutes(User $user): bool
    {
        $routes = $this->guardService->getGuardRoutesForUser($user);

        return 0 != count($routes);
    }

    public function guardRoute(string $route): bool
    {
        $token = $this->tokenStorage->getToken();

        return $this->guardService->guardRoute($route, $token);
    }

    public function guardRouteEnableGroupe(Route $route, Groupe $groupe): bool
    {
        return $this->guardService->guardRouteEnableGroupe($route, $groupe);
    }

    public function guardRouteEnableUser(Route $route, User $user): bool
    {
        return $this->guardService->guardRouteEnableUser($route, $user);
    }
}
