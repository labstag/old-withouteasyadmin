<?php

namespace Labstag\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Labstag\Form\Security\LoginType;
use LogicException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form         = $this->createForm(
            LoginType::class,
            ['username' => $lastUsername]
        );

        return $this->render(
            'security/login.html.twig',
            [
                'formLogin' => $form->createView(),
                'error'     => $error,
            ]
        );
    }

    /**
     * @Route("/lost", name="app_lost")
     *
     */
    public function lost()
    {

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
