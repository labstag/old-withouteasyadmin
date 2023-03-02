<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Labstag\Service\RepositoryService;
use Labstag\Service\WorkflowService;
use Psr\EventDispatcher\EventDispatcherInterface;

abstract class RequestHandlerLib
{
    public function __construct(
        protected RepositoryService $repositoryService,
        protected EntityManagerInterface $entityManager,
        protected WorkflowService $workflowService,
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function changeWorkflowState(mixed $entity, array $states): void
    {
        if (!$this->workflowService->has($entity)) {
            return;
        }

        $workflow = $this->workflowService->get($entity);
        foreach ($states as $state) {
            if (!$workflow->can($entity, $state)) {
                continue;
            }

            $workflow->apply($entity, $state);
        }

        $repository = $this->repositoryService->get($entity::class);
        $repository->add($entity);
    }

    public function handle(mixed $oldEntity, mixed $entity): void
    {
        $repository = $this->repositoryService->get($entity::class);
        $repository->add($entity);
        if ($oldEntity->getId() != $entity->getId()) {
            $this->initWorkflow($entity);
        }
    }

    protected function initWorkflow(mixed $entity): void
    {
        if (!$this->workflowService->has($entity)) {
            return;
        }

        $workflow = $this->workflowService->get($entity);
        $definition = $workflow->getDefinition();
        $transitions = $definition->getTransitions();
        foreach ($transitions as $transition) {
            $name = $transition->getName();
            if (!$workflow->can($entity, $name)) {
                continue;
            }

            $workflow->apply($entity, $name);
            $repository = $this->repositoryService->get($entity::class);
            $repository->add($entity);

            break;
        }
    }

    protected function setArrayCollectionUser(User $user): void
    {
        $userCollectionEvent = new UserCollectionEvent();
        $oauthConnectUsers = $user->getOauthConnectUsers();
        foreach ($oauthConnectUsers as $oauthConnectUser) {
            // @var OauthConnectUser $row
            $old = clone $oauthConnectUser;
            $oauthConnectUser->setRefuser($user);
            $userCollectionEvent->addOauthConnectUser($old, $oauthConnectUser);
        }

        $linkUsers = $user->getLinkUsers();
        foreach ($linkUsers as $linkUser) {
            // @var LinkUser $row
            $old = clone $linkUser;
            $linkUser->setRefuser($user);
            $userCollectionEvent->addLinkUser($old, $linkUser);
        }

        $emailUsers = $user->getEmailUsers();
        foreach ($emailUsers as $emailUser) {
            // @var EmailUser $row
            $old = clone $emailUser;
            $emailUser->setRefuser($user);
            $userCollectionEvent->addEmailUser($old, $emailUser);
        }

        $phoneUsers = $user->getPhoneUsers();
        foreach ($phoneUsers as $phoneUser) {
            // @var PhoneUser $row
            $old = clone $phoneUser;
            $phoneUser->setRefuser($user);
            $userCollectionEvent->addPhoneUser($old, $phoneUser);
        }

        $addressUsers = $user->getAddressUsers();
        foreach ($addressUsers as $addressUser) {
            // @var AddressUser $row
            $old = clone $addressUser;
            $addressUser->setRefuser($user);
            $userCollectionEvent->addAddressUser($old, $addressUser);
        }
    }
}
