<?php

namespace Labstag\Controller;

use Labstag\Entity\User;
use Labstag\Form\Security\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Repository\UserRepository;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
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
