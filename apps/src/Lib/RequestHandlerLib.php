<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Registry;

abstract class RequestHandlerLib
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected Registry $registry,
        protected EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function changeWorkflowState($entity, array $states): void
    {
        if (!$this->registry->has($entity)) {
            return;
        }

        $workflow = $this->registry->get($entity);
        foreach ($states as $state) {
            if (!$workflow->can($entity, $state)) {
                continue;
            }

            $workflow->apply($entity, $state);
        }

        $repository = $this->getRepository($entity::class);
        $repository->add($entity);
    }

    public function handle($oldEntity, $entity)
    {
        $repository = $this->getRepository($entity::class);
        $repository->add($entity);
        if ($oldEntity->getId() != $entity->getId()) {
            $this->initWorkflow($entity);
        }
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function initWorkflow($entity): void
    {
        if (!$this->registry->has($entity)) {
            return;
        }

        $workflow    = $this->registry->get($entity);
        $definition  = $workflow->getDefinition();
        $transitions = $definition->getTransitions();
        foreach ($transitions as $transition) {
            $name = $transition->getName();
            if (!$workflow->can($entity, $name)) {
                continue;
            }

            $workflow->apply($entity, $name);
            $repository = $this->getRepository($entity::class);
            $repository->add($entity);

            break;
        }
    }

    protected function setArrayCollectionUser(User $user)
    {
        $userCollectionEvent = new UserCollectionEvent();
        $oauthConnectUsers   = $user->getOauthConnectUsers();
        foreach ($oauthConnectUsers as $oauthConnectUser) {
            // @var OauthConnectUser $row
            $old = clone $oauthConnectUser;
            $oauthConnectUser->setRefuser($user);
            $userCollectionEvent->addOauthConnectUser($old, $oauthConnectUser);
        }

        $linksUsers = $user->getLinkUsers();
        foreach ($linksUsers as $linkUser) {
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
