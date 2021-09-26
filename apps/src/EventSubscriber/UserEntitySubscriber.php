<?php

namespace Labstag\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserEntitySubscriber implements EventSubscriberInterface
{

    protected EmailUserRequestHandler $emailUserRH;

    protected EntityManagerInterface $entityManager;

    protected FlashBagInterface $flashbag;

    protected UserPasswordHasherInterface $passwordEncoder;

    protected RequestStack $requestStack;

    protected TranslatorInterface $translator;

    protected UserMailService $userMailService;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordEncoder,
        UserMailService $userMailService,
        EmailUserRequestHandler $emailUserRH,
        TranslatorInterface $translator
    )
    {
        $this->translator      = $translator;
        $this->requestStack    = $requestStack;
        $this->emailUserRH     = $emailUserRH;
        $this->userMailService = $userMailService;
        $this->entityManager   = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [UserEntityEvent::class => 'onUserEntityEvent'];
    }

    public function onUserEntityEvent(UserEntityEvent $event): void
    {
        $oldEntity = $event->getOldEntity();
        $newEntity = $event->getNewEntity();
        $this->setPassword($newEntity);
        $this->setPrincipalMail($oldEntity, $newEntity);
        $this->setChangePassword($oldEntity, $newEntity);
        $this->setDeletedAt($oldEntity, $newEntity);
    }

    protected function setChangePassword(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getState() == $newEntity->getState()) {
            return;
        }

        if ('lostpassword' != $oldEntity->getState()) {
            return;
        }

        $this->userMailService->changePassword($newEntity);
        $this->flashBagAdd(
            'success',
            'Changement de mot de passe effectué'
        );
    }

    protected function setDeletedAt(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getDeletedAt() == $newEntity->getDeletedAt()) {
            return;
        }

        $states = [
            'adresseUsers' => $newEntity->getAdresseUsers(),
            'editos'       => $newEntity->getEditos(),
            'emailUsers'   => $newEntity->getEmailUsers(),
            'noteInternes' => $newEntity->getNoteInternes(),
            'lienUsers'    => $newEntity->getLienUsers(),
            'phoneUsers'   => $newEntity->getPhoneUsers(),
            'posts'        => $newEntity->getPosts(),
        ];

        $datetime = $newEntity->getDeletedAt();
        foreach ($states as $data) {
            foreach ($data as $entity) {
                $entity->setDeletedAt($datetime);
                $this->entityManager->persist($entity);
            }

            $this->entityManager->flush();
        }
    }

    protected function setPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if ('' === $plainPassword || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->passwordEncoder->hashPassword(
            $user,
            $plainPassword
        );

        $user->setPassword($encodePassword);
        if ('valider' == $user->getState()) {
            $this->userMailService->changePassword($user);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.password.change')
        );
    }

    protected function setPrincipalMail(User $oldEntity, User $newEntity): void
    {
        if ($oldEntity->getEmail() == $newEntity->getEmail()) {
            return;
        }

        $adresse = $newEntity->getEmail();
        $emails  = $newEntity->getEmailUsers();
        $trouver = false;
        foreach ($emails as $emailUser) {
            // @var EmailUser $emailUser
            $emailUser->setPrincipal(false);
            if ($emailUser->getAdresse() === $adresse) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }

            $this->entityManager->persist($emailUser);
        }

        if ('valider' == $newEntity->getState()) {
            $this->userMailService->changeEmailPrincipal($newEntity);
        }

        $this->entityManager->flush();
        $this->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.emailprincal.change')
        );

        if ($trouver) {
            return;
        }

        $emailUser = new EmailUser();
        $old       = clone $emailUser;
        $emailUser->setRefuser($newEntity);
        $emailUser->setPrincipal(true);
        $emailUser->setAdresse($adresse);
        $this->emailUserRH->handle($old, $emailUser);
        $this->emailUserRH->changeWorkflowState($emailUser, ['submit', 'valider']);
    }

    private function flashBagAdd(string $type, $message)
    {
        $requestStack = $this->requestStack;
        $request      = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }
}
