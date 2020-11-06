<?php

namespace Labstag\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Labstag\Entity\User;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener implements EventSubscriber
{

    /**
     * @var RouterInterface|Router
     */
    protected $router;

    /**
     * password Encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

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

        $this->plainPassword($entity);
        // $manager  = $args->getEntityManager();
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

        $this->plainPassword($entity);
        $manager = $args->getEntityManager();
        $meta    = $manager->getClassMetadata(get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $meta,
            $entity
        );
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
