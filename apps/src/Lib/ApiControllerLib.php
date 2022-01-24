<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\Service\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Workflow\Registry;

abstract class ApiControllerLib extends AbstractController
{

    protected Request $request;

    public function __construct(
        protected RequestStack $requeststack,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $token,
        protected PhoneService $phoneService,
        protected EntityManagerInterface $entityManager,
        protected AttachmentRequestHandler $attachmentRH,
        protected Registry $workflows
    )
    {
        // @var Request $request
        $request       = $this->requeststack->getCurrentRequest();
        $this->request = $request;
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }
}
