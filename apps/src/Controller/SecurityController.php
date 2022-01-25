<?php

namespace Labstag\Controller;

use Labstag\Entity\Email;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\Phone;
use Labstag\Entity\User;
use Labstag\Form\Security\ChangePasswordType;
use Labstag\Form\Security\DisclaimerType;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Lib\ControllerLib;
use Labstag\RequestHandler\EmailRequestHandler;
use Labstag\RequestHandler\PhoneRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\DataService;
use Labstag\Service\UserService;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends ControllerLib
{
    /**
     * @Route("/change-password/{id}", name="app_changepassword", priority=1)
     */
    public function changePassword(
        User $user,
        Request $request,
        UserRequestHandler $requestHandler
    ): Response
    {
        if ('lostpassword' != $user->getState()) {
            $this->sessionService->flashBagAdd(
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

        return $this->renderForm(
            'security/change-password.html.twig',
            ['formChangePassword' => $form]
        );
    }

    /**
     * @Route("/confirm/email/{id}", name="app_confirm_mail", priority=1)
     */
    public function confirmEmail(
        Email $email,
        EmailRequestHandler $emailRequestHandler
    ): RedirectResponse
    {
        if ('averifier' != $email->getState()) {
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('security.email.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $emailRequestHandler->changeWorkflowState($email, ['valider']);
        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('security.email.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/confirm/phone/{id}", name="app_confirm_phone", priority=1)
     */
    public function confirmPhone(
        Phone $phone,
        PhoneRequestHandler $emailRequestHandler
    ): RedirectResponse
    {
        if ('averifier' != $phone->getState()) {
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('security.phone.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $emailRequestHandler->changeWorkflowState($phone, ['valider']);
        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('security.phone.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/confirm/user/{id}", name="app_confirm_user", priority=1)
     */
    public function confirmUser(
        User $user,
        UserRequestHandler $userRequestHandler
    ): RedirectResponse
    {
        if ('avalider' != $user->getState()) {
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('security.user.activate.fail')
            );

            return $this->redirectToRoute('front');
        }

        $userRequestHandler->changeWorkflowState($user, ['validation']);
        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('security.user.activate.win')
        );

        return $this->redirectToRoute('front');
    }

    /**
     * @Route("/disclaimer", name="disclaimer", priority=1)
     */
    public function disclaimer(Request $request, DataService $dataService): RedirectResponse|Response
    {
        $form = $this->createForm(DisclaimerType::class, []);
        $form->handleRequest($request);
        $session = $request->getSession();
        if ($form->isSubmitted()) {
            $post = $request->request->all($form->getName());
            if (isset($post['confirm'])) {
                $session->set('disclaimer', 1);

                return $this->redirectToRoute('front');
            }

            $this->sessionService->flashBagAdd(
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

        return $this->renderForm(
            'security/disclaimer.html.twig',
            [
                'class_body' => 'DisclaimerPage',
                'form'       => $form,
            ]
        );
    }

    /**
     * @Route("/login", name="app_login", priority=1)
     */
    public function login(
        AuthenticationUtils $authenticationUtils
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

        $oauths = $this->getRepository(OauthConnectUser::class)->findDistinctAllOauth();

        return $this->renderForm(
            'security/login.html.twig',
            [
                'oauths'    => $oauths,
                'formLogin' => $form,
                'error'     => $error,
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout", priority=1)
     */
    public function logout(): void
    {
        $msg = 'This method can be blank - it will be intercepted by the logout key on your firewall.';

        throw new LogicException($msg);
    }

    /**
     * @Route("/lost", name="app_lost", priority=1)
     */
    public function lost(
        Request $request,
        UserService $userService
    ): Response
    {
        $form = $this->createForm(LostPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->all($form->getName());
            $userService->postLostPassword($post);

            return $this->redirectToRoute('app_login');
        }

        return $this->renderForm(
            'security/lost-password.html.twig',
            ['formLostPassword' => $form]
        );
    }
}
