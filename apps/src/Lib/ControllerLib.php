<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\DataService;
use Labstag\Singleton\BreadcrumbsSingleton;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class ControllerLib extends AbstractController
{

    protected Breadcrumbs $breadcrumbs;

    protected BreadcrumbsSingleton $breadcrumbsInstance;

    protected DataService $dataService;

    protected PaginatorInterface $paginator;

    protected RequestStack $requestStack;

    public function __construct(
        RequestStack $requestStack,
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        PaginatorInterface $paginator
    )
    {
        $this->requestStack = $requestStack;
        $this->dataService  = $dataService;
        $this->breadcrumbs  = $breadcrumbs;
        $this->paginator    = $paginator;
        $this->setSingletons();
    }

    protected function flashBagAdd(string $type, $message)
    {
        $requestStack = $this->requestStack;
        $request      = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
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

    protected function setBreadcrumbs(): void
    {
        $data = $this->breadcrumbsInstance->get();
        foreach ($data as $title => $route) {
            $this->breadcrumbs->addItem($title, $route);
        }
    }

    protected function setSingletons()
    {
        $this->breadcrumbsInstance = BreadcrumbsSingleton::getInstance();
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
}
