<?php

namespace Labstag\Event\Subscriber;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Lib\EventSubscriberLib;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class IgnoreSoftDeleteSubscriber extends EventSubscriberLib
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return ['kernel.controller' => 'onKernelController'];
    }

    public function onKernelController(ControllerEvent $controllerEvent): void
    {
        $controller = $controllerEvent->getController();
        if (!is_array($controller)) {
            return;
        }

        [
            $controller,
            $method,
        ] = $controller;

        $this->ignoreSoftDeleteAnnotation($controller, $method);
    }

    protected function ignoreSoftDeleteAnnotation(
        object $controller,
        string $method
    ): void {
        /** @var Request $request */
        $request      = $this->requestStack->getCurrentRequest();
        $routeCurrent = $request->attributes->get('_route');
        if (!is_string($routeCurrent)) {
            return;
        }

        $routes = [
            'api_action_destroies',
            'api_action_restories',
            'api_action_deleties',
            'api_action_emptyall',
            'api_action_empties',
            '_trash',
            '_preview',
            '_destroy',
            '_empty',
            '_restore',
        ];

        $find = 0;
        foreach ($routes as $route) {
            if (0 != substr_count($routeCurrent, $route)) {
                $find = 1;

                break;
            }
        }

        if (0 == $find) {
            return;
        }

        if ($this->readAnnotation($controller, $method)) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }
    }

    protected function readAnnotation(
        object $controller,
        string $method
    ): bool {
        $status           = false;
        $reflectionClass  = new ReflectionClass($controller::class);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $attributes       = $reflectionMethod->getAttributes();
        foreach ($attributes as $attribute) {
            if (IgnoreSoftDelete::class != $attribute->getName()) {
                continue;
            }

            $status = true;

            break;
        }

        return $status;
    }
}
