<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\DataService;
use Labstag\Service\GuardService;
use Labstag\Singleton\BreadcrumbsSingleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ControllerLib extends AbstractController
{

    protected BreadcrumbsSingleton $breadcrumbsInstance;

    protected Request $request;

    public function __construct(
        protected Environment $twig,
        protected EntityManagerInterface $entityManager,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $tokenStorage,
        protected RouterInterface $routerInterface,
        protected RequestStack $requeststack,
        protected GuardService $guardService,
        protected DataService $dataService,
        protected PaginatorInterface $paginator,
        protected TranslatorInterface $translator
    )
    {
        $this->breadcrumbsInstance = BreadcrumbsSingleton::getInstance();
    }

    protected function flashBagAdd(string $type, $message)
    {
        $request = $this->requeststack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $this->requeststack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function setErrorLogger($exception, $logger)
    {
        $errorMsg = sprintf(
            'Exception : Erreur %s dans %s L.%s : %s',
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage()
        );
        $logger->error($errorMsg);
    }

    protected function setSingletons()
    {
        return $this->breadcrumbsInstance;
    }
}
