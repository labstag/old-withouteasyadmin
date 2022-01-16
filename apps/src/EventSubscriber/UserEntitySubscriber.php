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

    protected FlashBagInterface $flashbag;

    public function __construct(protected RequestStack $requestStack, protected EntityManagerInterface $entityManager, protected UserPasswordHasherInterface $passwordEncoder, protected UserMailService $userMailService, protected EmailUserRequestHandler $emailUserRH, protected TranslatorInterface $translator)
    {
    }

    public static function getSubscribedEvents(): array
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
            'addressUsers' => $newEntity->getAddressUsers(),
            'bookmarks'    => $newEntity->getBookmarks(),
            'editos'       => $newEntity->getEditos(),
            'emailUsers'   => $newEntity->getEmailUsers(),
            'linkUsers'    => $newEntity->getLinkUsers(),
            'noteInternes' => $newEntity->getMemos(),
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

        $address = $newEntity->getEmail();
        $emails  = $newEntity->getEmailUsers();
        $trouver = false;
        foreach ($emails as $emailUser) {
            // @var EmailUser $emailUser
            $emailUser->setPrincipal(false);
            if ($emailUser->getAddress() === $address) {
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
        $emailUser->setAddress($address);
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
