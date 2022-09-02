<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\BlockService;
use Labstag\Service\DataService;
use Labstag\Service\ErrorService;
use Labstag\Service\FileService;
use Labstag\Service\GuardService;
use Labstag\Service\MenuService;
use Labstag\Service\SessionService;
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
        protected FileService $fileService,
        protected Environment $twig,
        protected ErrorService $errorService,
        protected SessionService $sessionService,
        protected EntityManagerInterface $entityManager,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $tokenStorage,
        protected RouterInterface $routerInterface,
        protected RequestStack $requeststack,
        protected GuardService $guardService,
        protected DataService $dataService,
        protected PaginatorInterface $paginator,
        protected TranslatorInterface $translator,
        protected BlockService $blockService,
        protected MenuService $menuService
    )
    {
        $this->breadcrumbsInstance = BreadcrumbsSingleton::getInstance();
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function setSingletons()
    {
        return $this->breadcrumbsInstance;
    }
}
