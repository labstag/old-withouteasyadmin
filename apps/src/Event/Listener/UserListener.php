<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;
use Labstag\Service\SessionService;
use Labstag\Service\UserMailService;
use Labstag\Service\WorkflowService;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserListener implements EventSubscriberInterface
{
    public function __construct(
        protected WorkflowService $workflowService,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected UserMailService $userMailService,
        protected UserRepository $userRepository,
        protected SessionService $sessionService,
        protected TranslatorInterface $translator,
        protected LoggerInterface $logger
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('persist', $lifecycleEventArgs);
    }

    public function postRemove(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('remove', $lifecycleEventArgs);
    }

    public function postUpdate(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('update', $lifecycleEventArgs);
    }

    protected function setChangePassword(User $user): void
    {
        if ($user->getState()) {
            return;
        }

        if ('lostpassword' != $user->getState()) {
            return;
        }

        $this->userMailService->changePassword($user);
        $this->sessionService->flashBagAdd(
            'success',
            'Changement de mot de passe effectué'
        );
    }

    protected function setPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();
        if ('' === $plainPassword || is_null($plainPassword)) {
            return;
        }

        $encodePassword = $this->userPasswordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPlainPassword('');

        $user->setPassword($encodePassword);
        if ('valider' == $user->getState()) {
            $this->userMailService->changePassword($user);
        }

        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.password.change')
        );
    }

    protected function setPrincipalMail(User $user): void
    {
        $email      = $user->getEmail();
        $emailUsers = $user->getEmailUsers();
        $trouver    = false;
        foreach ($emailUsers as $emailUser) {
            /** @var EmailUser $emailUser */
            $emailUser->setPrincipal(false);
            if ($emailUser->getAddress() === $email) {
                $emailUser->setPrincipal(true);
                $trouver = true;
            }
        }

        if ('valider' == $user->getState()) {
            $this->userMailService->changeEmailPrincipal($user);
        }

        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('user.subscriber.emailprincal.change')
        );

        if ($trouver) {
            return;
        }

        $emailUser = new EmailUser();
        $emailUser->setRefuser($user);
        $emailUser->setPrincipal(true);
        $emailUser->setAddress($email);

        $user->addEmailUser($emailUser);
        $this->workflowService->changeState($emailUser, ['submit', 'valider']);
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof User) {
            return;
        }

        $this->logger->info($action.' '.$object::class);
        $this->setPassword($object);
        $this->setPrincipalMail($object);
        $this->setChangePassword($object);
    }
}
