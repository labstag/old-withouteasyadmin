<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEntitySubscriber implements EventSubscriberInterface
{

    private SessionInterface $session;

    private EntityManagerInterface $entityManager;

    private UserPasswordEncoderInterface $passwordEncoder;

    private UserMailService $userMailService;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserMailService $userMailService
    )
    {
        $this->userMailService = $userMailService;
        $this->entityManager   = $entityManager;
        $this->session         = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function onUserEntityEvent(UserEntityEvent $event): void
    {
        $oldEntity = $event->getOldEntity();
        $newEntity = $event->getNewEntity();
        $this->setPassword($newEntity);
        $this->setPrincipalMail($oldEntity, $newEntity);
        $this->setLost($oldEntity, $newEntity);
        $this->setEnable($oldEntity, $newEntity);
        $this->setChangePassword($oldEntity, $newEntity);
    }

    private function setChangePassword(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->isLost() == $newEntity->isLost()) {
            return;
        }

        if (!$oldEntity->isLost()) {
            return;
        }

        /** @var Session $session */
        $session = $this->session;
        $this->userMailService->changePassword($newEntity);
        $session->getFlashBag()->add(
            'success',
            'Changement de mot de passe effectué'
        );
    }

    private function setLost(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->isLost() == $newEntity->isLost()) {
            return;
        }

        if (!$newEntity->isLost()) {
            return;
        }

        $this->userMailService->lostPassword($newEntity);
        /** @var Session $session */
        $session = $this->session;
        $session->getFlashBag()->add(
            'success',
            'Demande de nouveau mot de passe envoyé'
        );
    }

    private function setEnable(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->isVerif() == $newEntity->isVerif()) {
            return;
        }

        if ($newEntity->isVerif()) {
            return;
        }

        $this->userMailService->newUser($newEntity);
        /** @var Session $session */
        $session = $this->session;
        $session->getFlashBag()->add(
            'success',
            'Nouveau compte utilisateur créer'
        );
    }

    private function setPrincipalMail(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getEmail() == $newEntity->getEmail()) {
            return;
        }

        $adresse = $newEntity->getEmail();
        $emails  = $newEntity->getEmailUsers();
        $trouver = false;
        foreach ($emails as $emailUser) {
            /** @var EmailUser $emailUser */
            $emailUser->setPrincipal(false);
            if ($emailUser->getAdresse() === $adresse) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }

            $this->entityManager->persist($emailUser);
        }

        if ($newEntity->isEnable()) {
            $this->userMailService->changeEmailPrincipal($newEntity);
        }

        $this->entityManager->flush();
        /** @var Session $session */
        $session = $this->session;
        $session->getFlashBag()->add(
            'success',
            'Email principal changé'
        );

        if ($trouver) {
            return;
        }

        $emailUser = new EmailUser();
        $emailUser->setRefuser($newEntity);
        $emailUser->setVerif(true);
        $emailUser->setPrincipal(true);
        $emailUser->setAdresse($adresse);
        $this->entityManager->persist($emailUser);
        $this->entityManager->flush();
    }

    private function setPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if ($plainPassword === '' || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->passwordEncoder->encodePassword(
            $user,
            $plainPassword
        );

        $user->setPassword($encodePassword);
        if ($user->isEnable()) {
            $this->userMailService->changePassword($user);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        /** @var Session $session */
        $session = $this->session;
        $session->getFlashBag()->add(
            'success',
            'Mot de passe changé'
        );
    }

    public static function getSubscribedEvents()
    {
        return [UserEntityEvent::class => 'onUserEntityEvent'];
    }
}
