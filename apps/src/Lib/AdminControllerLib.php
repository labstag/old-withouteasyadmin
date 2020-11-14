<?php

namespace Labstag\Lib;

use Symfony\Component\HttpFoundation\Request;

abstract class AdminControllerLib extends ControllerLib
{
    protected function newForm(
        Request $request,
        $form,
        $entity
    ): bool
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return true;
        }

        return false;
    }

    protected function editForm(Request $request, $form): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return true;
        }

        return false;
    }


    protected function deleteEntity(
        Request $request,
        $entity
    ): bool
    {
        $token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entity);
            $entityManager->flush();
            return true;
        }

        return false;
    }
}
