<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\DataService;
use Labstag\Service\GuardService;
use Labstag\Singleton\BreadcrumbsSingleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ControllerLib extends AbstractController
{

    protected Breadcrumbs $breadcrumbs;

    protected BreadcrumbsSingleton $breadcrumbsInstance;

    protected DataService $dataService;

    protected GuardService $guardService;

    protected PaginatorInterface $paginator;

    protected Request $request;

    protected RequestStack $requestStack;

    protected TranslatorInterface $translator;

    protected Environment $twig;

    public function __construct(
        GuardService $guardService,
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator,
        TranslatorInterface $translator
    )
    {
        $this->guardService = $guardService;
        $this->translator   = $translator;
        $this->dataService  = $dataService;
        $this->breadcrumbs  = $breadcrumbs;
        $this->paginator    = $paginator;
        $this->setSingletons();
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $this->setBreadcrumbs();
        if (isset($this->headerTitle) && '' != $this->headerTitle) {
            $parameters['headerTitle'] = $this->headerTitle;
        }

        return parent::render($view, $parameters, $response);
    }

    protected function flashBagAdd(string $type, $message)
    {
        $requestStack = $this->get('request_stack');
        $request      = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }

    protected function setBreadcrumbs(): void
    {
        $data = $this->breadcrumbsInstance->get();
        foreach ($data as $title => $route) {
            $this->breadcrumbs->addItem($title, $route);
        }
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
        $this->breadcrumbsInstance = BreadcrumbsSingleton::getInstance();
    }
}
