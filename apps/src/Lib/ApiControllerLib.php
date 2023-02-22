<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\Service\PhoneService;
use Labstag\Service\WorkflowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class ApiControllerLib extends AbstractController
{

    protected Request $request;

    public function __construct(
        protected RequestStack $requeststack,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $tokenStorage,
        protected PhoneService $phoneService,
        protected EntityManagerInterface $entityManager,
        protected AttachmentRequestHandler $attachmentRequestHandler,
        protected WorkflowService $workflowService,
        protected UserRepository $userRepository
    )
    {
        // @var Request $request
        $this->request = $this->requeststack->getCurrentRequest();
    }

    protected function getGuardRouteOrWorkflow($data, $get, $entityClass)
    {
        if (!array_key_exists('user', $get)) {
            return $data;
        }

        $data['user'] = [];
        $user = $this->userRepository->find($get['user']);
        if (!$user instanceof User) {
            return $data;
        }

        /** @var ServiceEntityRepositoryLib $entityRepository */
        $entityRepository = $this->entityManager->getRepository($entityClass);

        $results = $entityRepository->findEnableByUser($user);
        if (RouteUser::class == $entityClass) {
            foreach ($results as $row) {
                // @var RouteUser $row
                $data['user'][] = [
                    'route' => $row->getRefroute()->getName(),
                ];
            }

            return $data;
        }

        foreach ($results as $result) {
            // @var WorkflowGroupe $row
            $data['group'][] = [
                'entity'     => $result->getRefworkflow()->getEntity(),
                'transition' => $result->getRefworkflow()->getTransition(),
            ];
        }

        return $data;
    }

    protected function getResultWorkflow($request, $entity)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var ServiceEntityRepositoryLib $entityRepository */
        $entityRepository = $this->entityManager->getRepository($entity);
        $get = $request->query->all();
        if (array_key_exists('user', $get)) {
            $user = $userRepository->find($get['user']);

            return $entityRepository->findEnableByGroupe($user->getRefgroupe());
        }

        return $entityRepository->findEnableByGroupe();
    }
}
