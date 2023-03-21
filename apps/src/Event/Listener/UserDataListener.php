<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\AddressUser;
use Labstag\Entity\LinkUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Interfaces\EntityInterface;
use Labstag\Interfaces\UserDataInterface;
use Labstag\Service\UserMailService;
use Psr\Log\LoggerInterface;

class UserDataListener implements EventSubscriberInterface
{
    public function __construct(
        protected LoggerInterface $logger,
        protected UserMailService $userMailService
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('persist', $lifecycleEventArgs);
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof UserDataInterface) {
            return;
        }

        $this->logger->info($action.' '.get_class($object));

        
        $this->setOauthConnectUser($object);
        $this->setLinkUser($object);
        $this->setAddressUser($object);
    }

    private function setAddressUser(UserDataInterface $entity): void
    {
        if (!$entity instanceof AddressUser) {
            return;
        }

        /** @var User $user */
        $user = $entity->getRefuser();

        $this->userMailService->checkNewAddress(
            $user,
            $entity
        );
    }

    private function setOauthConnectUser(UserDataInterface $entity): void
    {
        if (!$entity instanceof OauthConnectUser) {
            return;
        }

        /** @var User $user */
        $user = $entity->getRefuser();
        $this->userMailService->checkNewOauthConnectUser(
            $user,
            $entity
        );
    }

    private function setLinkUser(UserDataInterface $entity): void
    {
        if (!$entity instanceof LinkUser) {
            return;
        }
        
        /** @var User $user */
        $user = $entity->getRefuser();
        $this->userMailService->checkNewLink(
            $user,
            $entity
        );
    }
}
