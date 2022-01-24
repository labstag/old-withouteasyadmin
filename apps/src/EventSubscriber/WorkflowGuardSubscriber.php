<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class WorkflowGuardSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $token
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
        $workflow    = $this->getRepository(Workflow::class)->findOneBy(
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
            $groupe         = $this->getRepository(Groupe::class)->findOneBy(['code' => 'visiteur']);
            $workflowGroupe = $this->getRepository(WorkflowGroupe::class)->findOneBy(
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

        $workflowGroupe = $this->getRepository(WorkflowGroupe::class)->findOneBy(
            [
                'refgroupe'   => $groupe,
                'refworkflow' => $workflow,
            ]
        );
        $stategroupe    = ($workflowGroupe instanceof WorkflowGroupe) ? $workflowGroupe->getState() : $stategroupe;
        $workflowUser   = $this->getRepository(WorkflowUser::class)->findOneBy(
            [
                'refuser'     => $user,
                'refworkflow' => $workflow,
            ]
        );
        $stategroupe    = ($workflowUser instanceof WorkflowUser) ? $workflowUser->getState() : $stategroupe;

        $event->setBlocked(!$stategroupe || !$stateuser);
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }
}
