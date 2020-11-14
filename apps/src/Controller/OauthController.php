<?php

namespace Labstag\Controller;

use Exception;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\GeneralControllerLib;
use Labstag\Lib\GenericProviderLib;
use Labstag\Service\OauthService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Labstag\Repository\OauthConnectUserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class OauthController extends GeneralControllerLib
{

    private OauthService $oauthService;

    private LoggerInterface $logger;

    public function __construct(
        OauthService $oauthService,
        LoggerInterface $logger
    )
    {
        $this->logger       = $logger;
        $this->oauthService = $oauthService;
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/lost/{oauthCode}", name="connect_lost")
     */
    public function lost(
        Request $request,
        string $oauthCode,
        Security $security,
        OauthConnectUserRepository $repository
    ): RedirectResponse
    {
        /** @var User $user */
        $user = $security->getUser();
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        $session = $request->getSession();
        $session->set('referer', $referer);
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        /**
         * @var OauthConnectUser
         */
        $entity  = $repository->findOneOauthByUser($oauthCode, $user);
        $manager = $this->getDoctrine()->getManager();
        if ($entity instanceof OauthConnectUser) {
            $manager->remove($entity);
            $manager->flush();
            $this->addFlash(
                'success',
                'Connexion Oauh '.$oauthCode.' dissocié'
            );
        }

        return $this->redirect($referer);
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/{oauthCode}", name="connect_start")
     */
    public function connect(
        Request $request,
        string $oauthCode
    ): RedirectResponse
    {
        /** @var GenericProviderLib $provider */
        $provider = $this->oauthService->setProvider($oauthCode);
        $session  = $request->getSession();
        /** @var string $referer */
        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if (!($provider instanceof GenericProviderLib)) {
            $this->addFlash('warning', 'Connexion Oauh impossible');

            return $this->redirect($referer);
        }

        $authUrl = $provider->getAuthorizationUrl();
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        $session->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    private function ifBug($provider, $query, $oauth2state)
    {
        $bug = 0;
        if (!($provider instanceof GenericProviderLib)) {
            $bug = 1;
        }

        if (!isset($query['code']) || $oauth2state !== $query['state']) {
            $bug = 1;
        }

        return $bug;
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/connect/{oauthCode}/check", name="connect_check")
     */
    public function connectCheck(
        Request $request,
        string $oauthCode
    ): RedirectResponse
    {
        /** @var GenericProviderLib $provider */
        $provider    = $this->oauthService->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $referer     = $session->get('referer');
        $oauth2state = $session->get('oauth2state');
        /** @var string $url */
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if ($this->ifBug($provider, $query, $oauth2state)) {
            $session->remove('oauth2state');
            $session->remove('referer');
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }

        try {
            /** @var AccessToken $tokenProvider */
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            $session->remove('referer');
            /** @var UsageTrackingTokenStorage $tokenStorage */
            $tokenStorage = $this->get('security.token_storage');
            /** @var TokenInterface $token */
            $token = $tokenStorage->getToken();
            if (!($token instanceof AnonymousToken)) {
                $userOauth = $provider->getResourceOwner($tokenProvider);
                $user      = $token->getUser();
                if (!is_object($user) || !($user instanceof User)) {
                    $this->addFlash('warning', "Probleme d'identification");

                    return $this->redirect($referer);
                }

                /* @var User $user */
                $this->addOauthToUser($oauthCode, $user, $userOauth);
            }

            return $this->redirect($referer);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $this->addFlash('warning', "Probleme d'identification");

            return $this->redirect($referer);
        }
    }

    /**
     * @param mixed $oauthConnect
     */
    private function findOAuthIdentity(
        User $user,
        string $identity,
        string $client,
        &$oauthConnect = null
    ): bool
    {
        $oauthConnects = $user->getOauthConnectUsers();
        foreach ($oauthConnects as $oauthConnect) {
            $test1 = ($oauthConnect->getName() == $client);
            $test2 = ($oauthConnect->getIdentity() == $identity);
            if ($test1 && $test2) {
                return true;
            }
        }

        $oauthConnect = null;

        return false;
    }

    /**
     * @param GenericResourceOwner|ResourceOwnerInterface $userOauth
     */
    private function addOauthToUser(
        string $client,
        User $user,
        $userOauth
    ): void
    {
        $data     = $userOauth->toArray();
        $identity = $this->oauthService->getIdentity($data, $client);
        $find     = $this->findOAuthIdentity(
            $user,
            $identity,
            $client,
            $oauthConnect
        );
        $manager  = $this->getDoctrine()->getManager();
        /** @var OauthConnectUserRepository $repository */
        $repository = $manager->getRepository(OauthConnectUser::class);
        if (false === $find) {
            /** @var OauthConnectUser|null $oauthConnect */
            $oauthConnect = $repository->findOauthNotUser(
                $user,
                $identity,
                $client
            );
            if (is_null($oauthConnect)) {
                $oauthConnect = new OauthConnectUser();
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
            $oauthConnect->setData($userOauth->toArray());
            $manager->persist($oauthConnect);
            $manager->flush();
            $this->addFlash('success', 'Compte associé');

            return;
        }

        $this->addFlash('warning', "Impossible d'associer le compte");
    }
}
