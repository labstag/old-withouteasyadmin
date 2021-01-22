<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionObject;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class IgnoreSoftDeleteSubscriber implements EventSubscriberInterface
{

    const ANNOTATION = 'Labstag\Annotation\IgnoreSoftDelete';

    private Reader $reader;

    private EntityManagerInterface $entityManager;

    private RequestStack $requestStack;

    /**
     *
     * @var Request|null
     */
    private $request;

    public function __construct(
        Reader $reader,
        EntityManagerInterface $entityManager,
        RequestStack $requestStack
    )
    {
        $this->requestStack = $requestStack;
        /** @var Request $request */
        $request             = $this->requestStack->getCurrentRequest();
        $this->request       = $request;
        $this->reader        = $reader;
        $this->entityManager = $entityManager;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }

        list($controller, $method, ) = $controller;

        $this->ignoreSoftDeleteAnnotation($controller, $method);
    }

    private function readAnnotation($controller, $method, $annotation)
    {
        $utils           = new ClassUtils();
        $classReflection = new ReflectionClass($utils->getClass($controller));
        $classAnnotation = $this->reader->getClassAnnotation($classReflection, $annotation);

        $objectReflection = new ReflectionObject($controller);
        $methodReflection = $objectReflection->getMethod($method);
        $methodAnnotation = $this->reader->getMethodAnnotation($methodReflection, $annotation);

        if (!$classAnnotation && !$methodAnnotation) {
            return false;
        }

        return [
            $classAnnotation,
            $classReflection,
            $methodAnnotation,
            $methodReflection,
        ];
    }

    private function ignoreSoftDeleteAnnotation($controller, $method)
    {
        $routeCurrent = $this->request->get('_route');
        $routes       = [
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

        if ($this->readAnnotation($controller, $method, self::ANNOTATION)) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.controller' => 'onKernelController'];
    }
}
