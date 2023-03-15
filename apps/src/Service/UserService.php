<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Repository\UserRepository;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    public function __construct(
        protected WorkflowService $workflowService,
        protected OauthService $oauthService,
        protected SessionService $sessionService,
        protected RequestStack $requestStack,
        protected EntityManagerInterface $entityManager,
        protected TranslatorInterface $translator,
        protected OauthConnectUserRepository $oauthConnectUserRepository,
        protected UserRepository $userRepository
    )
    {
    }

    public function addOauthToUser(
        string $client,
        User $user,
        ResourceOwnerInterface $resourceOwner
    ): void
    {
        $repository = $this->oauthConnectUserRepository;
        $identity   = $this->oauthService->getIdentity($resourceOwner->toArray(), $client);
        if (!is_string($identity)) {
            return;
        }

        $find = $this->findOAuthIdentity(
            $user,
            $identity,
            $client,
            $oauthConnect
        );
        if (false === $find) {
            /** @var null|OauthConnectUser $oauthConnect */
            $oauthConnect = $repository->findOauthNotUser(
                $user,
                $identity,
                $client
            );
            if (is_null($oauthConnect)) {
                $oauthConnect = new OauthConnectUser();
                $oauthConnect->setIdentity($identity);
                $oauthConnect->setRefuser($user);
                $oauthConnect->setName($client);
            }

            /** @var User $refuser */
            $refuser = $oauthConnect->getRefuser();
            if ($refuser->getId() !== $user->getId()) {
                $oauthConnect = null;
            }
        }

        if ($oauthConnect instanceof OauthConnectUser) {
            $oauthConnect->setData($resourceOwner->toArray());
            $repository->add($oauthConnect);
            $this->sessionService->flashBagAdd(
                'success',
                $this->translator->trans('service.user.oauth.sucess')
            );

            return;
        }

        $this->sessionService->flashBagAdd(
            'warning',
            $this->translator->trans('service.user.oauth.fail')
        );
    }

    public function create(
        array $groupes,
        array $dataUser
    ): User
    {
        $user = new User();
        $user->setRefgroupe($this->getRefgroupe($groupes, $dataUser['groupe']));
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);

        $this->userRepository->add($user);
        $this->workflowService->changeState($user, $dataUser['state']);

        return $user;
    }

    public function ifBug(
        AbstractProvider $provider,
        array $query,
        ?string $oauth2state
    ): bool
    {
        if (is_null($oauth2state)) {
            return true;
        }

        if (!$provider instanceof AbstractProvider) {
            return true;
        }

        return (bool) (!isset($query['code']) || $oauth2state !== $query['state']);
    }

    public function postLostPassword(array $post): void
    {
        if ('' === $post['value']) {
            return;
        }

        /** @var User $user */
        $user = $this->userRepository->findUserEnable($post['value']);
        if (!$user instanceof User) {
            return;
        }

        $this->workflowService->changeState($user, ['lostpassword']);
    }

    protected function findOAuthIdentity(
        User $user,
        string $identity,
        string $client,
        ?OauthConnectUser &$oauthConnectUser = null
    ): bool
    {
        $return            = false;
        $oauthConnectUsers = $user->getOauthConnectUsers();
        foreach ($oauthConnectUsers as $oauthConnect) {
            /** @var OauthConnectUser $oauthConnect */
            $test1 = ($oauthConnect->getName() == $client);
            $test2 = ($oauthConnect->getIdentity() == $identity);
            if ($test1 && $test2) {
                $return           = true;
                $oauthConnectUser = $oauthConnect;

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
}
