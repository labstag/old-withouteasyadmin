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
        return [
            new TwigFilter('guard_group_access', [$this, 'guardAccessGroupRoutes']),
            new TwigFilter('guard_route', [$this, 'guardRoute']),
            new TwigFilter('guard_user_access', [$this, 'guardAccessUserRoutes']),
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('guard_route_enable_group', [$this, 'guardRouteEnableGroupe']),
        ];
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
}
