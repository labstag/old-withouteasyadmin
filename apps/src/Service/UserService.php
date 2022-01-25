<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\RequestHandler\OauthConnectUserRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{

    protected FlashBagInterface $flashbag;

    public function __construct(
        protected RequestStack $requestStack,
        protected EntityManagerInterface $entityManager,
        protected UserRequestHandler $userRH,
        protected OauthConnectUserRequestHandler $oauthConnectUserRH,
        protected TranslatorInterface $translator
    )
    {
    }

    public function create($groupes, $dataUser)
    {
        $user = new User();
        $old  = clone $user;

        $user->setRefgroupe($this->getRefgroupe($groupes, $dataUser['groupe']));
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $this->userRH->handle($old, $user);
        $this->userRH->changeWorkflowState($user, $dataUser['state']);

        return $user;
    }

    public function postLostPassword(array $post): void
    {
        if ('' === $post['value']) {
            return;
        }

        // @var User $user
        $user = $this->getRepository(User::class)->findUserEnable($post['value']);
        if (!$user instanceof User) {
            return;
        }

        $this->userRH->changeWorkflowState($user, ['lostpassword']);
    }

    protected function findOAuthIdentity(
        User $user,
        string $identity,
        string $client,
        &$oauthConnect = null
    ): bool
    {
        $return        = false;
        $oauthConnect  = null;
        $oauthConnects = $user->getOauthConnectUsers();
        foreach ($oauthConnects as $oauthConnect) {
            $test1 = ($oauthConnect->getName() == $client);
            $test2 = ($oauthConnect->getIdentity() == $identity);
            if ($test1 && $test2) {
                $return = true;

                break;
            }
        }

        return $return;
    }

    protected function getRefgroupe(array $groupes, string $code): ?Groupe
    {
        $return = null;
        foreach ($groupes as $groupe) {
            if ($groupe->getCode() != $code) {
                continue;
            }

            $return = $groupe;

            break;
        }

        return $return;
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }
}
