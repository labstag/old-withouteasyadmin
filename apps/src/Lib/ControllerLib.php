<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Interfaces\EntityInterface;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Service\AdminBtnService;
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
        protected WorkflowService $workflowService,
        protected RepositoryService $repositoryService,
        protected UploadAnnotationReader $uploadAnnotationReader,
        protected FrontService $frontService,
        protected DomainService $domainService,
        protected AttachFormService $attachFormService,
        protected FileService $fileService,
        protected Environment $twigEnvironment,
        protected ErrorService $errorService,
        protected SessionService $sessionService,
        protected EntityManagerInterface $entityManager,
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

    protected function changeWorkflowState(EntityInterface $entity, array $states): void
    {
        if (!$this->workflowService->has($entity)) {
            return;
        }

        /** @var WorkflowInterface $workflow */
        $workflow = $this->workflowService->get($entity);
        foreach ($states as $state) {
            if (!$workflow->can($entity, $state)) {
                continue;
            }

            $workflow->apply($entity, $state);
        }

        /** @var ServiceEntityRepositoryLib $repository */
        $repository = $this->repositoryService->get($entity::class);
        $repository->add($entity);
    }

    protected function initWorkflow(EntityInterface $entity): void
    {
        if (!$this->workflowService->has($entity)) {
            return;
        }

        /** @var WorkflowInterface $workflow */
        $workflow    = $this->workflowService->get($entity);
        $definition  = $workflow->getDefinition();
        $transitions = $definition->getTransitions();
        foreach ($transitions as $transition) {
            $name = $transition->getName();
            if (!$workflow->can($entity, $name)) {
                continue;
            }

            $workflow->apply($entity, $name);
            /** @var ServiceEntityRepositoryLib $repository */
            $repository = $this->repositoryService->get($entity::class);
            $repository->add($entity);

            break;
        }
    }
}
