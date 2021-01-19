<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionObject;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class IgnoreSoftDeleteSubscriber implements EventSubscriberInterface
{

    private Reader $reader;

    private EntityManagerInterface $entityManager;

    public function __construct(Reader $reader, EntityManagerInterface $entityManager) {
        $this->reader = $reader;
        $this->entityManager = $entityManager;
    }

    public function onKernelController(ControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        list($controller, $method, ) = $controller;

        $this->ignoreSoftDeleteAnnotation($controller, $method);
    }

    private function readAnnotation($controller, $method, $annotation) {
        $classReflection = new ReflectionClass(ClassUtils::getClass($controller));
        $classAnnotation = $this->reader->getClassAnnotation($classReflection, $annotation);

        $objectReflection = new ReflectionObject($controller);
        $methodReflection = $objectReflection->getMethod($method);
        $methodAnnotation = $this->reader->getMethodAnnotation($methodReflection, $annotation);

        if (!$classAnnotation && !$methodAnnotation) {
            return false;
        }

        return [$classAnnotation, $classReflection, $methodAnnotation, $methodReflection];
    }

    private function ignoreSoftDeleteAnnotation($controller, $method) {
        $class = 'Labstag\Annotation\IgnoreSoftDelete';
        
        if ($this->readAnnotation($controller, $method, $class)) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
