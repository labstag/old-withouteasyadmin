<?php

namespace Labstag\Lib;

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
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ControllerLib extends AbstractController
{

    protected Breadcrumbs $breadcrumbs;

    protected BreadcrumbsSingleton $breadcrumbsInstance;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected DataService $dataService;

    protected GuardService $guardService;

    protected PaginatorInterface $paginator;

    protected Request $request;

    protected RequestStack $requeststack;

    protected RouterInterface $routerInterface;

    protected TokenStorageInterface $tokenStorage;

    protected TranslatorInterface $translator;

    protected Environment $twig;

    public function __construct(
        Environment $twig,
        CsrfTokenManagerInterface $csrfTokenManager,
        TokenStorageInterface $tokenStorage,
        RouterInterface $routerInterface,
        RequestStack $requestStack,
        GuardService $guardService,
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator,
        TranslatorInterface $translator
    )
    {
        $this->csrfTokenManager    = $csrfTokenManager;
        $this->tokenStorage        = $tokenStorage;
        $this->routerInterface     = $routerInterface;
        $this->twig                = $twig;
        $this->guardService        = $guardService;
        $this->translator          = $translator;
        $this->dataService         = $dataService;
        $this->breadcrumbs         = $breadcrumbs;
        $this->paginator           = $paginator;
        $this->requeststack        = $requestStack;
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
