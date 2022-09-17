<?php

namespace Labstag\Controller\Api;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Lib\ApiControllerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Service\TrashService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/actions')]
class ActionsController extends ApiControllerLib
{
    #[Route(path: '/delete/{entity}/{id}', name: 'api_action_delete', methods: ['DELETE'])]
    public function delete(string $entity, string $id): Response
    {
        $data   = [
            'action' => false,
            'error'  => '',
        ];
        $entity = $this->getDataRestoreDelete($entity, $id);
        if (is_null($entity) || !is_null($entity->getDeletedAt())) {
            $data['error'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->tokenVerif('delete', $entity);
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $this->deleteEntity($entity);

        return new JsonResponse($data);
    }

    #[Route(path: '/deleties/{entity}/', name: 'api_action_deleties', methods: ['DELETE'])]
    public function deleties(string $entity, Request $request): Response
    {
        return $this->deleteOrRestore($entity, $request, 'deleties');
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/destroies/{entity}', name: 'api_action_destroies', methods: ['DELETE'])]
    public function destroies(string $entity, Request $request): Response
    {
        return $this->deleteOrRestore($entity, $request, 'destroies');
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/destroy/{entity}/{id}', name: 'api_action_destroy', methods: ['DELETE'])]
    public function destroy(string $entity, string $id): Response
    {
        $data       = [
            'action' => false,
            'error'  => '',
        ];
        $repository = $this->getRepoByEntity($entity);
        $entity     = $repository->find($id);
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            $data['error'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->tokenVerif('destroy', $entity);
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $this->destroyEntity($entity);

        return new JsonResponse($data);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/empties', name: 'api_action_empties', methods: ['DELETE'])]
    public function empties(Request $request): Response
    {
        $data       = [
            'action' => false,
            'error'  => '',
        ];
        $tokenValid = $this->tokenVerif('empties');
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $entities = explode(',', (string) $request->request->get('entities'));
        $error    = [];
        foreach ($entities as $entity) {
            $repository = $this->getRepoByEntity($entity);

            try {
                $this->deleteEntityByRepository($repository);
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        $data['error'] = $error;
        if ([] === $error) {
            $data['action'] = true;
        }

        return new JsonResponse($data);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/empty/{entity}', name: 'api_action_empty', methods: ['DELETE'])]
    public function empty(string $entity): Response
    {
        $data       = [
            'action' => false,
            'error'  => '',
        ];
        $repository = $this->getRepoByEntity($entity);
        $tokenValid = $this->tokenVerif('empty');
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $error = [];

        try {
            $this->deleteEntityByRepository($repository);
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
     * @IgnoreSoftDelete
     */
    #[Route(path: '/emptyall', name: 'api_action_emptyall', methods: ['DELETE'])]
    public function emptyall(TrashService $trashService): Response
    {
        $tokenValid = $this->tokenVerif('emptyall');
        $data       = [
            'action' => false,
            'error'  => '',
        ];
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $error         = $this->deleteAll($trashService);
        $data['error'] = $error;
        if (0 === (is_countable($error) ? count($error) : 0)) {
            $data['action'] = true;
        }

        return new JsonResponse($data);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/restore/{entity}/{id}', name: 'api_action_restore', methods: ['POST'])]
    public function restore(string $entity, string $id): Response
    {
        $data   = [
            'action' => false,
            'error'  => '',
        ];
        $entity = $this->getDataRestoreDelete($entity, $id);
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            $data['error'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->tokenVerif('restore', $entity);
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $data['action'] = true;
        $this->restoreEntity($entity);

        return new JsonResponse($data);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/restories/{entity}', name: 'api_action_restories', methods: ['POST'])]
    public function restories(string $entity, Request $request): Response
    {
        return $this->deleteOrRestore($entity, $request, 'restories');
    }

    #[Route(path: '/workflow/{entity}/{state}/{id}', name: 'api_action_workflow', methods: ['POST'])]
    public function workflow(string $entity, string $state, string $id): Response
    {
        $data       = [
            'action' => false,
            'error'  => '',
        ];
        $repository = $this->getRepoByEntity($entity);
        $entity     = $repository->find($id);
        $this->denyAccessUnlessGranted('workflow-'.$state, $entity);
        if (is_null($entity)) {
            $data['error'] = 'entité inconnu';

            return new JsonResponse($data);
        }

        $tokenValid = $this->tokenVerif('workflow-'.$state, $entity);
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

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

    protected function getRepoByEntity(string $entity): ?ServiceEntityRepositoryLib
    {
        $repositories = $this->setRepository();
        if (isset($repositories[$entity])) {
            return $this->entityManager->getRepository($repositories[$entity]);
        }

        return null;
    }

    protected function setRepository(): array
    {
        $files        = glob($this->getParameter('kernel.project_dir').'/src/Entity/*.php');
        $repositories = [];
        foreach ($files as $file) {
            $path                                = pathinfo((string) $file);
            $filename                            = $path['filename'];
            $repositories[strtolower($filename)] = 'Labstag\\Entity\\'.$filename;
        }

        return $repositories;
    }

    private function deleteAll(TrashService $trashService)
    {
        $all   = $trashService->all();
        $error = [];
        foreach ($all as $data) {
            $entity     = $data['name'];
            $repository = $this->getRepoByEntity($entity);

            try {
                $this->deleteEntityByRepository($repository);
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        return $error;
    }

    private function deleteEntity($entity)
    {
        if (is_null($entity) || !is_null($entity->getDeletedAt())) {
            return;
        }

        $repository = $this->getRepository($entity::class);
        $repository->remove($entity);
    }

    private function deleteEntityByRepository($repository)
    {
        $all   = $repository->findTrashForAdmin([]);
        $files = [];
        foreach ($all as $entity) {
            $repository->remove($entity);
            if (!$entity instanceof Attachment) {
                continue;
            }

            $files[] = $entity->getName();
        }

        foreach ($files as $file) {
            if ('' == $file && is_file($file)) {
                continue;
            }

            unlink($file);
        }
    }

    private function deleteOrRestore(string $entity, Request $request, string $token)
    {
        $data = [
            'action' => false,
            'error'  => '',
        ];

        $tokenValid = $this->tokenVerif($token);
        if (!$tokenValid) {
            $data['error'] = 'token incorrect';

            return new JsonResponse($data);
        }

        $entities   = explode(',', (string) $request->request->get('entities'));
        $error      = [];
        $repository = $this->getRepoByEntity($entity);
        $method     = ('deleties' == $token) ? 'deleteEntity' : 'restoreEntity';
        $method     = ('destroies' == $token) ? 'destroyEntity' : $method;
        foreach ($entities as $id) {
            try {
                $entity = $repository->find($id);
                call_user_func([$this, $method], $entity);
            } catch (Exception $exception) {
                $error[] = $exception->getMessage();
            }
        }

        $data['error'] = $error;
        if ([] === $error) {
            $data['action'] = true;
        }

        unset($entity);

        return new JsonResponse($data);
    }

    private function destroyEntity($entity)
    {
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            return;
        }

        $file       = '';
        $repository = $this->getRepository($entity::class);
        $repository->remove($entity);
        if ($entity instanceof Attachment) {
            $file = $entity->getName();
        }

        if ('' != $file && is_file($file)) {
            unlink($file);
        }
    }

    private function getDataRestoreDelete($entity, $id)
    {
        $repository = $this->getRepoByEntity($entity);

        return $repository->find($id);
    }

    private function restoreEntity($entity)
    {
        if (is_null($entity) || is_null($entity->getDeletedAt())) {
            return;
        }

        $entity->setDeletedAt(null);
        $repository = $this->getRepository($entity::class);
        $repository->add($entity);
    }

    private function tokenVerif(string $action, $entity = null): bool
    {
        $token = $this->requeststack->getCurrentRequest()->get('_token');

        $csrfToken = new CsrfToken(
            $action.(is_null($entity) ? '' : $entity->getId()),
            $token
        );

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }
}
