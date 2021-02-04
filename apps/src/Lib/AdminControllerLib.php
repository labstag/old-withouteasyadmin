<?php

namespace Labstag\Lib;

use Labstag\Service\AdminCrudService;
use Labstag\Service\DataService;
use Labstag\Singleton\AdminBtnSingleton;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;
use Twig\Environment;

abstract class AdminControllerLib extends ControllerLib
{

    protected string $headerTitle = '';

    protected string $urlHome = '';

    protected AdminCrudService $adminCrudService;

    protected Environment $twig;

    protected AdminBtnSingleton $btnInstance;

    protected RouterInterface $router;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(
        DataService $dataService,
        AdminCrudService $adminCrudService,
        Breadcrumbs $breadcrumbs,
        Environment $twig,
        CsrfTokenManagerInterface $csrfTokenManager,
        RouterInterface $router
    )
    {
        $this->twig             = $twig;
        $this->router           = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->adminCrudService = $adminCrudService;
        $this->adminCrudService->setController($this);
        $this->adminCrudService->setPage($this->headerTitle, $this->urlHome);
        $this->setSingletonsAdmin();
        $this->adminCrudService->setBtnInstance($this->btnInstance);
        parent::__construct($dataService, $breadcrumbs);
    }

    protected function setSingletonsAdmin()
    {
        $btnInstance = AdminBtnSingleton::getInstance();
        if (!$btnInstance->isInit()) {
            $btnInstance->setConf($this->twig, $this->router, $this->csrfTokenManager);
        }

        $this->btnInstance = $btnInstance;
    }

    public function addBreadcrumbs(array $breadcrumbs): void
    {
        $this->breadcrumbsInstance->add($breadcrumbs);
    }

    protected function setBreadcrumbsPage()
    {
        if ($this->headerTitle == '' && $this->urlHome == '') {
            return;
        }

        $router      = $this->get('router');
        $breadcrumbs = [
            $this->headerTitle => $router->generate(
                $this->urlHome
            ),
        ];

        $this->breadcrumbsInstance->addPosition($breadcrumbs, 0);
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $this->setBreadcrumbsPage();
        $parameters = array_merge(
            $parameters,
            [
                'btnadmin' => $this->btnInstance->get(),
            ]
        );
        return parent::render($view, $parameters, $response);
    }
}
