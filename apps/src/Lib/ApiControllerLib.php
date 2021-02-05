<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Repository\RouteRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\Service\ApiActionsService;
use Labstag\Service\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Workflow\Registry;

class ApiControllerLib extends AbstractController
{

    protected ApiActionsService $apiActionsService;

    protected EntityManagerInterface $entityManager;

    protected Registry $workflows;

    protected PhoneService $phoneService;

    protected RequestStack $requestStack;

    protected Request $request;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected TokenStorageInterface $token;

    protected AttachmentRequestHandler $attachmentRH;

    protected RouteRepository $routeRepo;

    public function __construct(
        RequestStack $requestStack,
        CsrfTokenManagerInterface $csrfTokenManager,
        TokenStorageInterface $token,
        PhoneService $phoneService,
        RouteRepository $routeRepo,
        EntityManagerInterface $entityManager,
        AttachmentRequestHandler $attachmentRH,
        Registry $workflows,
        ApiActionsService $apiActionsService
    )
    {
        $this->routeRepo        = $routeRepo;
        $this->attachmentRH     = $attachmentRH;
        $this->token            = $token;
        $this->requestStack     = $requestStack;
        $this->entityManager    = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        /** @var Request $request */
        $request                 = $this->requestStack->getCurrentRequest();
        $this->request           = $request;
        $this->phoneService      = $phoneService;
        $this->workflows         = $workflows;
        $this->apiActionsService = $apiActionsService;
    }
}
