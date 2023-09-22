<?php

namespace Labstag\PostForm\Security;

use Labstag\Form\Security\LostPasswordType;
use Labstag\Interfaces\PostFormInterface;
use Labstag\Lib\PostFormLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LostPasswordForm extends PostFormLib implements PostFormInterface
{
    public function context(array $params): mixed
    {
        $form = $this->createForm($this->getForm());
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->all($form->getName());
            $this->userService->postLostPassword($post);

            return $this->redirectToRoute('app_login');
        }

        return array_merge(
            $params,
            ['form' => $form]
        );
    }

    public function execute(string $template, array $params): ?Response
    {
        $form = $this->createForm($this->getForm());
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->all($form->getName());
            $this->userService->postLostPassword($post);

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            $template,
            array_merge(
                $params,
                ['form' => $form]
            )
        );
    }

    public function getForm(): string
    {
        return LostPasswordType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('security-lostpassword.name', [], 'postform');
    }
}
