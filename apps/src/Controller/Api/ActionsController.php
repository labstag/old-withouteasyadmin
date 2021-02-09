<?php

namespace Labstag\Controller\Api;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/actions")
 */
class ActionsController extends ApiControllerLib
{
    /**
     * @Route("/empties", name="api_action_empties", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function empties(string $entity): JsonResponse
    {
        return new JsonResponse([]);
    }
    /**
     * @Route("/emptyall", name="api_action_emptyall", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function emptyall(string $entity): JsonResponse
    {
        return new JsonResponse([]);
    }
    /**
     * @Route("/empty/{entity}", name="api_action_empty", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function empty(string $entity): JsonResponse
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $repository = $this->apiActionsService->getRepository($entity);
        $tokenValid = $this->apiActionsService->verifToken('empty');
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $all            = $repository->findTrashForAdmin();
        $files          = [];
        foreach ($all as $entity) {
            $this->entityManager->remove($entity);
            if ($entity instanceof Attachment) {
                $files[] = $entity->getName();
            }
        }

        $this->entityManager->flush();
        foreach ($files as $file) {
            if ('' != $file && is_file($file)) {
                unlink($file);
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/restore/{entity}/{id}", name="api_action_restore", methods={"POST"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function restore(string $entity, string $id): JsonResponse
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $repository = $this->apiActionsService->getRepository($entity);
        $entity     = $repository->find($id);
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            $data['message'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->apiActionsService->verifToken('restore', $entity);
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $entity->setDeletedAt(null);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return new JsonResponse($data);
    }

    /**
     * @Route("/destroy/{entity}/{id}", name="api_action_destroy", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function destroy(string $entity, string $id): JsonResponse
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $repository = $this->apiActionsService->getRepository($entity);
        $entity     = $repository->find($id);
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            $data['message'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->apiActionsService->verifToken('destroy', $entity);
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $this->entityManager->remove($entity);
        if ($entity instanceof Attachment) {
            $file = $entity->getName();
        }

        $this->entityManager->flush();
        if ('' != $file && is_file($file)) {
            unlink($file);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/delete/{entity}/{id}", name="api_action_delete", methods={"DELETE"})
     *
     * @return Response
     */
    public function delete(string $entity, string $id): JsonResponse
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $repository = $this->apiActionsService->getRepository($entity);
        $entity     = $repository->find($id);
        if (is_null($entity) || !is_null($entity->getDeletedAt())) {
            $data['message'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->apiActionsService->verifToken('delete', $entity);
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return new JsonResponse($data);
    }

    /**
     * @Route("/workflow/{entity}/{state}/{id}", name="api_action_workflow", methods={"POST"})
     */
    public function workflow(string $entity, string $state, string $id): Response
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $repository = $this->apiActionsService->getRepository($entity);
        $entity     = $repository->find($id);
        $this->denyAccessUnlessGranted('workflow-'.$state, $entity);
        if (is_null($entity)) {
            $data['message'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->apiActionsService->verifToken('workflow-'.$state, $entity);
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        if ($this->workflows->has($entity)) {
            $workflow = $this->workflows->get($entity);
            $workflow->apply($entity, $state);
            $this->entityManager->flush();
        }

        return new JsonResponse($data);
    }
}
