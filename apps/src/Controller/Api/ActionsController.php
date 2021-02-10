<?php

namespace Labstag\Controller\Api;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Lib\ApiControllerLib;
use Labstag\Service\TrashService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function empties(Request $request): JsonResponse
    {
        $data       = [
            'action'  => false,
            'message' => '',
        ];
        $tokenValid = $this->apiActionsService->verifToken('empties');
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $entities = explode(',', $request->request->get('entities'));
        $error    = [];
        foreach ($entities as $entity) {
            $repository = $this->apiActionsService->getRepository($entity);
            try {
                $this->deleteEntity($repository);
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        $data['message'] = $error;
        if (0 === count($error)) {
            $data['action'] = true;
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/emptyall", name="api_action_emptyall", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function emptyall(TrashService $trashService): JsonResponse
    {
        $tokenValid = $this->apiActionsService->verifToken('emptyall');

        $data = [
            'action'  => false,
            'message' => '',
        ];
        if (!$tokenValid) {
            $data['message'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $error           = $this->deleteAll($trashService);
        $data['message'] = $error;
        if (0 === count($error)) {
            $data['action'] = true;
        }

        return new JsonResponse($data);
    }

    private function deleteAll(TrashService $trashService)
    {
        $all   = $trashService->all();
        $error = [];
        foreach ($all as $data) {
            $entity     = $data['name'];
            $repository = $this->apiActionsService->getRepository($entity);
            try {
                $this->deleteEntity($repository);
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        return $error;
    }

    private function deleteEntity($repository)
    {
        $all   = $repository->findTrashForAdmin();
        $files = [];
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

        $error = [];
        try {
            $this->deleteEntity($repository);
        } catch (Exception $exception) {
            $error[] = $exception->getMessage();
        }

        $data['error'] = $error;
        if (0 === count($error)) {
            $data['action'] = true;
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
     * @Route("/destroy/{entity}", name="api_action_destroies", methods={"DELETE"})
     * @IgnoreSoftDelete
     *
     * @return Response
     */
    public function destroies(string $entity): JsonResponse
    {
        unset($entity);
        return new JsonResponse([]);
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
     * @Route("/delete/{entity}/", name="api_action_deleties", methods={"DELETE"})
     *
     * @return Response
     */
    public function deleties(string $entity): JsonResponse
    {
        unset($entity);
        return new JsonResponse([]);
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
