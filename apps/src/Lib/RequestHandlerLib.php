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
        protected Registry $workflows,
        protected EventDispatcherInterface $dispatcher
    )
    {
    }

    public function changeWorkflowState($entity, array $states): void
    {
        if (!$this->workflows->has($entity)) {
            return;
        }

        $workflow = $this->workflows->get($entity);
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
        if (!$this->workflows->has($entity)) {
            return;
        }

        $workflow    = $this->workflows->get($entity);
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

    protected function setArrayCollectionUser(User $entity)
    {
        $userCollectionEvent = new UserCollectionEvent();
        $oauthConnectUsers   = $entity->getOauthConnectUsers();
        foreach ($oauthConnectUsers as $row) {
            // @var OauthConnectUser $row
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addOauthConnectUser($old, $row);
        }

        $linksUsers = $entity->getLinkUsers();
        foreach ($linksUsers as $row) {
            // @var LinkUser $row
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addLinkUser($old, $row);
        }

        $emailUsers = $entity->getEmailUsers();
        foreach ($emailUsers as $row) {
            // @var EmailUser $row
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addEmailUser($old, $row);
        }

        $phoneUsers = $entity->getPhoneUsers();
        foreach ($phoneUsers as $row) {
            // @var PhoneUser $row
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addPhoneUser($old, $row);
        }

        $addressUsers = $entity->getAddressUsers();
        foreach ($addressUsers as $row) {
            // @var AddressUser $row
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addAddressUser($old, $row);
        }
    }
}
