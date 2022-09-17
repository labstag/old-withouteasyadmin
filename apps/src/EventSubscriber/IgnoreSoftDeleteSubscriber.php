<?php

namespace Labstag\EventSubscriber;

use Labstag\Annotation\IgnoreSoftDelete;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class IgnoreSoftDeleteSubscriber implements EventSubscriberInterface
{
    /**
     * @var class-string<IgnoreSoftDelete>
     */
    final public const ANNOTATION = 'Labstag\Annotation\IgnoreSoftDelete';

    // @var null|Request
    protected $request;

    public function __construct(
        protected Reader $reader,
        protected EntityManagerInterface $entityManager,
        protected RequestStack $requestStack
    )
    {
        // @var Request $request
        $this->request       = $this->requestStack->getCurrentRequest();
    }

    public static function getSubscribedEvents(): array
    {
        return ['kernel.controller' => 'onKernelController'];
    }

    public function onKernelController(ControllerEvent $controllerEvent)
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

    protected function ignoreSoftDeleteAnnotation($controller, $method)
    {
        $routeCurrent = $this->request->attributes->get('_route');
        $routes       = [
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
            if (0 != substr_count((string) $routeCurrent, $route)) {
                $find = 1;

                break;
            }
        }

        if (0 == $find) {
            return;
        }

        if ($this->readAnnotation($controller, $method, self::ANNOTATION)) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }
    }

    protected function readAnnotation($controller, $method, $annotation)
    {
        $classUtils           = new ClassUtils();
        $reflectionClass = new ReflectionClass($classUtils->getClass($controller));
        $classAnnotation = $this->reader->getClassAnnotation($reflectionClass, $annotation);

        $reflectionObject = new ReflectionObject($controller);
        $reflectionMethod = $reflectionObject->getMethod($method);
        $methodAnnotation = $this->reader->getMethodAnnotation($reflectionMethod, $annotation);

        if (!$classAnnotation && !$methodAnnotation) {
            return false;
        }

        return [
            $classAnnotation,
            $reflectionClass,
            $methodAnnotation,
            $reflectionMethod,
        ];
    }
}
