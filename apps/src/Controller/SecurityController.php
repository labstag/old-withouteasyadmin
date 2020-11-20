<?php

namespace Labstag\Controller;

use Exception;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Lib\GenericProviderLib;
use Labstag\Form\Security\ChangePasswordType;
use Labstag\Form\Security\DisclaimerType;
use Symfony\Component\HttpFoundation\Response;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Lib\GeneralControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Repository\UserRepository;
use Labstag\Service\DataService;
use Labstag\Service\OauthService;
use LogicException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class SecurityController extends GeneralControllerLib
{

    private OauthService $oauthService;

    private LoggerInterface $logger;

    public function __construct(
        OauthService $oauthService,
        LoggerInterface $logger,
        DataService $dataService
    )
    {
        $this->logger       = $logger;
        $this->oauthService = $oauthService;
        parent::__construct($dataService);
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/oauth/lost/{oauthCode}", name="connect_lost")
     */
    public function oauthLost(
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
     * @Route("/oauth/connect/{oauthCode}", name="connect_start")
     */
    public function oauthConnect(
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
        $bug = false;
        if (!($provider instanceof GenericProviderLib)) {
            return true;
        }

        if (!isset($query['code']) || $oauth2state !== $query['state']) {
            return true;
        }

        return false;
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/oauth/connect/{oauthCode}/check", name="connect_check")
     */
    public function oauthConnectCheck(
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

    /**
     * @Route("/disclaimer", name="disclaimer")
     *
     * @return RedirectResponse|Response
     */
    public function disclaimer(Request $request)
    {
        $form = $this->createForm(DisclaimerType::class, []);
        $form->handleRequest($request);
        $session = $request->getSession();
        if ($form->isSubmitted()) {
            $post = $request->request->get($form->getName());
            if (isset($post['confirm'])) {
                $session->set('disclaimer', 1);

                return $this->redirect(
                    $this->generateUrl('front')
                );
            }

            $this->addFlash('danger', "Veuillez accepter l'énoncé");
        }

        if (1 == $session->get('disclaimer', 0)) {
            return $this->redirect(
                $this->generateUrl('front')
            );
        }

        return $this->render(
            'security/disclaimer.html.twig',
            [
                'class_body' => 'DisclaimerPage',
                'form'       => $form->createView(),
            ]
        );
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(
        AuthenticationUtils $authenticationUtils,
        OauthConnectUserRepository $repository
    ): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->createForm(
            LoginType::class,
            ['username' => $lastUsername]
        );

        $oauths = $repository->findDistinctAllOauth();

        return $this->render(
            'security/login.html.twig',
            [
                'oauths'    => $oauths,
                'formLogin' => $form->createView(),
                'error'     => $error,
            ]
        );
    }

    /**
     * @Route("/change-password/{id}", name="app_changepassword")
     */
    public function changePassword(User $user, Request $request): Response
    {
        if (!$user->isLost()) {
            $this->addFlash('danger', 'Demande de mot de passe non envoyé');

            return $this->redirect($this->generateUrl('front'), 302);
        }

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setLost(false);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Changement de mot de passe effectué');

            return $this->redirect($this->generateUrl('front'), 302);
        }

        return $this->render(
            'security/change-password.html.twig',
            [
                'formChangePassword' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/lost", name="app_lost")
     *
     */
    public function lost(Request $request, UserRepository $repository)
    {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->get($form->getName());
            $this->postLostPassword($post, $repository);
        }

        return $this->render(
            'security/lost-password.html.twig',
            [
                'formLostPassword' => $form->createView(),
            ]
        );
    }

    private function postLostPassword(
        array $post,
        UserRepository $repository
    ): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        if ('' === $post['value']) {
            return;
        }

        /** @var User $user */
        $user = $repository->findUserEnable($post['value']);
        if (!($user instanceof User)) {
            return;
        }

        $user->setLost(true);
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Demande de nouveau mot de passe envoyé');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $msg  = 'This method can be blank - it will be intercepted by the';
        $msg .= ' logout key on your firewall.';
        throw new LogicException($msg);
    }
}
