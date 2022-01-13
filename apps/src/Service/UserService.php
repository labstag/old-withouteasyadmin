<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\OauthConnectUserRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{

    protected EntityManagerInterface $entityManager;

    protected FlashBagInterface $flashbag;

    protected OauthConnectUserRequestHandler $oauthConnectUserRH;

    protected UserRepository $repository;

    protected RequestStack $requestStack;

    protected TranslatorInterface $translator;

    protected UserRequestHandler $userRH;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $entityManager,
        UserRepository $repository,
        UserRequestHandler $userRH,
        OauthConnectUserRequestHandler $oauthConnectUserRH,
        TranslatorInterface $translator
    )
    {
        $this->translator         = $translator;
        $this->userRH             = $userRH;
        $this->requestStack       = $requestStack;
        $this->entityManager      = $entityManager;
        $this->repository         = $repository;
        $this->oauthConnectUserRH = $oauthConnectUserRH;
    }

    public function postLostPassword(array $post): void
    {
        if ('' === $post['value']) {
            return;
        }

        // @var User $user
        $user = $this->repository->findUserEnable($post['value']);
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
}
