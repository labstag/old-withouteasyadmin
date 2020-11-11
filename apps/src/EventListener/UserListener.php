<?php

namespace Labstag\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\User;
use Doctrine\Common\EventSubscriber;
use Labstag\Entity\EmailUser;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener implements EventSubscriber
{

    protected RouterInterface $router;

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->router          = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Sur quoi écouter.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $manager = $args->getEntityManager();
        $this->plainPassword($entity);
        $this->setEmail($entity, $manager);
        // $meta = $manager->getClassMetadata(get_class($entity));
        // $manager->getUnitOfWork()->recomputeSingleEntityChangeSet(
        //     $meta,
        //     $entity
        // );
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }

        $manager = $args->getEntityManager();
        $this->plainPassword($entity);
        $this->setEmail($entity, $manager);
        $meta = $manager->getClassMetadata(get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $meta,
            $entity
        );
    }

    private function setEmail(User $entity, $manager): void
    {
        $adresse = $entity->getEmail();
        $emails  = $entity->getEmailUsers();
        $trouver = false;
        foreach ($emails as $emailUser) {
            /** @var EmailUser $emailUser */
            $emailUser->setPrincipal(false);
            if ($emailUser->getAdresse() == $adresse) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }

            $manager->persist($emailUser);
            $meta = $manager->getClassMetadata(get_class($emailUser));
            $manager->getUnitOfWork()->computeChangeSet($meta, $emailUser);
        }

        if (!$trouver) {
            $emailUser = new EmailUser();
            $emailUser->setRefuser($entity);
            $emailUser->setVerif(true);
            $emailUser->setPrincipal(true);
            $emailUser->setAdresse($adresse);
            $manager->persist($emailUser);
            $meta = $manager->getClassMetadata(get_class($emailUser));
            $manager->getUnitOfWork()->computeChangeSet($meta, $emailUser);
        }
    }

    private function plainPassword(User $entity): void
    {
        $plainPassword = $entity->getPlainPassword();
        if ('' === $plainPassword || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->passwordEncoder->encodePassword(
            $entity,
            $plainPassword
        );

        $entity->setPassword($encodePassword);
    }
}
