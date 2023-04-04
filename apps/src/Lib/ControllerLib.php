<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Service\Admin\BlockService as AdminBlockService;
use Labstag\Service\Admin\BookmarkService as AdminBookmarkService;
use Labstag\Service\Admin\LayoutService as AdminLayoutService;
use Labstag\Service\Admin\MenuService as AdminMenuService;
use Labstag\Service\AdminBtnService;
use Labstag\Service\AdminService;
use Labstag\Service\AttachFormService;
use Labstag\Service\BlockService;
use Labstag\Service\BreadcrumbService;
use Labstag\Service\DataService;
use Labstag\Service\DomainService;
use Labstag\Service\ErrorService;
use Labstag\Service\FileService;
use Labstag\Service\FrontService;
use Labstag\Service\GuardService;
use Labstag\Service\MenuService;
use Labstag\Service\ParagraphService;
use Labstag\Service\RepositoryService;
use Labstag\Service\SessionService;
use Labstag\Service\WorkflowService;
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

    protected Request $request;

    public function __construct(
        protected AdminBlockService $adminBlockService,
        protected AdminBookmarkService $adminBookmarkService,
        protected AdminLayoutService $adminLayoutService,
        protected AdminMenuService $adminMenuService,
        protected AdminService $adminService,
        protected EntityManagerInterface $entityManager,
        protected WorkflowService $workflowService,
        protected UploadAnnotationReader $uploadAnnotationReader,
        protected FrontService $frontService,
        protected DomainService $domainService,
        protected AttachFormService $attachFormService,
        protected FileService $fileService,
        protected Environment $twigEnvironment,
        protected ErrorService $errorService,
        protected SessionService $sessionService,
        protected RepositoryService $repositoryService,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $tokenStorage,
        protected RouterInterface $router,
        protected RequestStack $requeststack,
        protected ParagraphService $paragraphService,
        protected GuardService $guardService,
        protected DataService $dataService,
        protected PaginatorInterface $paginator,
        protected TranslatorInterface $translator,
        protected BlockService $blockService,
        protected MenuService $menuService,
        protected BreadcrumbService $breadcrumbService,
        protected AdminBtnService $adminBtnService
    )
    {
    }
}
