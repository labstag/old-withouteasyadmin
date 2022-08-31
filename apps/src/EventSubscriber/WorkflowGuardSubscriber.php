<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\User;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\WorkflowGroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Repository\WorkflowUserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class WorkflowGuardSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected TokenStorageInterface $token,
        protected WorkflowRepository $workflowRepo,
        protected GroupeRepository $groupeRepo,
        protected WorkflowGroupeRepository $workflowGroupeRepo,
        protected WorkflowUserRepository $workflowUserRepo
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['workflow.guard' => 'onWorkflowAttachmentGuard'];
    }

    public function onWorkflowAttachmentGuard(GuardEvent $event)
    {
        $stategroupe = false;
        $stateuser   = false;
        $token       = $this->token->getToken();
        $name        = $event->getWorkflowName();
        $transition  = $event->getTransition()->getName();
        $workflow    = $this->workflowRepo->findOneBy(
            [
                'entity'     => $name,
                'transition' => $transition,
            ]
        );

        if (is_null($token)) {
            return;
        }

        // @var User $user
        $user = $token->getUser();
        if (!$user instanceof User) {
            $groupe         = $this->groupeRepo->findOneBy(['code' => 'visiteur']);
            $workflowGroupe = $this->workflowGroupeRepo->findOneBy(
                [
                    'refgroupe'   => $groupe,
                    'refworkflow' => $workflow,
                ]
            );

            $stategroupe = ($workflowGroupe instanceof WorkflowGroupe) ? $workflowGroupe->getState() : $stategroupe;
            $event->setBlocked(!$stategroupe);

            return;
        }

        $groupe = $user->getRefgroupe();
        if ('superadmin' === $groupe->getCode()) {
            return;
        }

        $workflowGroupe = $this->workflowGroupeRepo->findOneBy(
            [
                'refgroupe'   => $groupe,
                'refworkflow' => $workflow,
            ]
        );
        $stategroupe    = ($workflowGroupe instanceof WorkflowGroupe) ? $workflowGroupe->getState() : $stategroupe;
        $workflowUser   = $this->workflowUserRepo->findOneBy(
            [
                'refuser'     => $user,
                'refworkflow' => $workflow,
            ]
        );
        $stategroupe    = ($workflowUser instanceof WorkflowUser) ? $workflowUser->getState() : $stategroupe;

        $event->setBlocked(!$stategroupe || !$stateuser);
    }
}
