<?php

namespace Labstag\Controller;

use Exception;
use Labstag\Entity\Email;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\Phone;
use Labstag\Entity\User;
use Labstag\Form\Security\ChangePasswordType;
use Labstag\Form\Security\DisclaimerType;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\RequestHandler\EmailRequestHandler;
use Labstag\RequestHandler\PhoneRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\DataService;
use Labstag\Service\OauthService;
use Labstag\Service\UserService;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use LogicException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends ControllerLib
{
    /**
     * @Route("/change-password/{id}", name="app_changepassword")
     */
    public function changePassword(
        User $user,
        Request $request,
        UserRequestHandler $requestHandler
    ): Response
    {
        if ('lostpassword' != $user->getState()) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('security.user.sendlostpassword.fail')
            );

            return $this->redirectToRoute('front');
        }

        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $requestHandler->changeWorkflowState($user, ['valider']);

            return $this->redirectToRoute('front');
        }

        return $this->render(
            'security/change-password.html.twig',
            [
                'formChangePassword' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/confirm/email/{id}", name="app_confirm_mail")
     */
    public function confirmEmail(
        Email $email,
        EmailRequestHandler $emailRequestHandler
    ): RedirectResponse
    {
        if ('averifier' != $email->getState()) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('security.email.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $emailRequestHandler->changeWorkflowState($email, ['valider']);
        $this->flashBagAdd(
            'success',
            $this->translator->trans('security.email.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/confirm/phone/{id}", name="app_confirm_phone")
     */
    public function confirmPhone(
        Phone $phone,
        PhoneRequestHandler $emailRequestHandler
    ): RedirectResponse
    {
        if ('averifier' != $phone->getState()) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('security.phone.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $emailRequestHandler->changeWorkflowState($phone, ['valider']);
        $this->flashBagAdd(
            'success',
            $this->translator->trans('security.phone.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/confirm/user/{id}", name="app_confirm_user")
     */
    public function confirmUser(
        User $user,
        UserRequestHandler $userRequestHandler
    ): RedirectResponse
    {
        if ('avalider' != $user->getState()) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('security.user.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $userRequestHandler->changeWorkflowState($user, ['validation']);
        $this->flashBagAdd(
            'success',
            $this->translator->trans('security.user.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/disclaimer", name="disclaimer")
     *
     * @return RedirectResponse|Response
     */
    public function disclaimer(Request $request, DataService $dataService): RedirectResponse|Response
    {
        $form = $this->createForm(DisclaimerType::class, []);
        $form->handleRequest($request);
        $session = $request->getSession();
        if ($form->isSubmitted()) {
            $post = $request->request->get($form->getName());
            if (isset($post['confirm'])) {
                $session->set('disclaimer', 1);

                return $this->redirectToRoute('front');
            }

            $this->flashBagAdd(
                'danger',
                $this->translator->trans('security.disclaimer.doaccept')
            );
        }

        $config = $dataService->getConfig();

        if (1 == $session->get('disclaimer', 0)
            || !isset($config['disclaimer'])
            || !isset($config['disclaimer']['activate'])
            || 1 != $config['disclaimer']['activate']
        ) {
            return $this->redirectToRoute('front');
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
        $this->denyAccessUnlessGranted('IS_ANONYMOUS');
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
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        $msg = 'This method can be blank - it will be intercepted by the logout key on your firewall.';

        throw new LogicException($msg);
    }

    /**
     * @Route("/lost", name="app_lost")
     */
    public function lost(
        Request $request,
        UserService $userService
    ): Response
    {
        $this->denyAccessUnlessGranted('IS_ANONYMOUS');
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->get($form->getName());
            $userService->postLostPassword($post);

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'security/lost-password.html.twig',
            [
                'formLostPassword' => $form->createView(),
            ]
        );
    }

    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/oauth/connect/{oauthCode}", name="connect_start")
     */
    public function oauthConnect(
        Request $request,
        string $oauthCode,
        OauthService $oauthService
    ): RedirectResponse
    {
        // @var AbstractProvider $provider
        $provider = $oauthService->setProvider($oauthCode);
        $session  = $request->getSession();
        // @var string $referer
        $query = $request->query->all();
        if (array_key_exists('link', $query)) {
            $session->set('link', 1);
        }

        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        // @var string $url
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if (!$provider instanceof AbstractProvider) {
            $this->flashBagAdd(
                'warning',
                $this->translator->trans('security.user.oauth.fail')
            );

            return $this->redirect($referer);
        }

        $authUrl = $provider->getAuthorizationUrl();
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $session->set('referer', $referer);
        $session->set('oauth2state', $provider->getState());

        return $this->redirect($authUrl);
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Route("/oauth/check/{oauthCode}", name="connect_check")
     */
    public function oauthConnectCheck(
        Request $request,
        string $oauthCode,
        LoggerInterface $logger,
        OauthService $oauthService,
        UserService $userService
    ): RedirectResponse
    {
        // @var AbstractProvider $provider
        $provider    = $oauthService->setProvider($oauthCode);
        $query       = $request->query->all();
        $session     = $request->getSession();
        $referer     = $session->get('referer');
        $oauth2state = $session->get('oauth2state');
        // @var string $url
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        if ($userService->ifBug($provider, $query, $oauth2state)) {
            $session->remove('oauth2state');
            $session->remove('referer');
            $session->remove('link');
            $this->flashBagAdd(
                'warning',
                $this->translator->trans('security.user.connect.fail')
            );

            return $this->redirect($referer);
        }

        try {
            // @var AccessToken $tokenProvider
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );

            $session->remove('oauth2state');
            // @var UsageTrackingTokenStorage $tokenStorage
            $tokenStorage = $this->get('security.token_storage');
            // @var TokenInterface $token
            $token = $tokenStorage->getToken();
            if (!$token instanceof AnonymousToken) {
                // @var ResourceOwnerInterface $userOauth
                $userOauth = $provider->getResourceOwner($tokenProvider);
                // @var User $user
                $user = $token->getUser();
                if (!$user instanceof User) {
                    $this->flashBagAdd(
                        'warning',
                        $this->translator->trans('security.user.connect.fail')
                    );

                    return $this->redirect($referer);
                }

                $userService->addOauthToUser(
                    $oauthCode,
                    $user,
                    $userOauth
                );
            }

            $session->remove('referer');
            $session->remove('link');

            return $this->redirect($referer);
        } catch (Exception $exception) {
            $this->setErrorLogger($exception, $logger);
            $this->flashBagAdd(
                'warning',
                $this->translator->trans('security.user.connect.fail')
            );
            $session->remove('referer');
            $session->remove('link');

            return $this->redirect($referer);
        }
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
        $this->denyAccessUnlessGranted('ROLE_USER');
        // @var User $user
        $user = $security->getUser();
        // @var string $referer
        $referer = $request->headers->get('referer');
        $session = $request->getSession();
        $session->set('referer', $referer);
        // @var string $url
        $url = $this->generateUrl('front');
        if ('' == $referer) {
            $referer = $url;
        }

        $entity  = $repository->findOneOauthByUser($oauthCode, $user);
        $manager = $this->getDoctrine()->getManager();
        if ($entity instanceof OauthConnectUser) {
            $manager->remove($entity);
            $manager->flush();
            $paramtrans = ['%string%' => $oauthCode];

            $msg = $this->translator->trans('security.user.oauth.dissociated', $paramtrans);
            $this->flashBagAdd('success', $msg);
        }

        return $this->redirect($referer);
    }
}
