<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Attachment;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Edito;
use Labstag\Entity\Memo;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/attachment')]
class AttachmentController extends ApiControllerLib
{
    #[Route(path: '/bookmark/img/{entity}', name: 'api_attachment_bookmarkimg')]
    public function bookmarkImg(AttachmentRepository $repository, Bookmark $entity, PostRequestHandler $postRequestHandler): Response
    {
        return $this->deleteFile($repository, $entity, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/edito/fond/{entity}', name: 'api_attachment_editofond')]
    public function editoFond(AttachmentRepository $repository, Edito $entity, EditoRequestHandler $editoRH): Response
    {
        return $this->deleteFile($repository, $entity, $editoRH, 'getFond', 'setFond');
    }

    #[Route(path: '/favicon', name: 'api_attachment_favicon')]
    public function favicon(
        AttachmentRepository $repository
    ): Response
    {
        return $this->setDataAttachment($repository, 'getFavicon');
    }

    #[Route(path: '/imagedefault', name: 'api_attachment_image')]
    public function imageDefault(
        AttachmentRepository $repository
    ): Response
    {
        return $this->setDataAttachment($repository, 'getImageDefault');
    }

    #[Route(path: '/memo/fond/{entity}', name: 'api_attachment_memofond')]
    public function memoFond(AttachmentRepository $repository, Memo $entity, MemoRequestHandler $noteInterneRH): Response
    {
        return $this->deleteFile($repository, $entity, $noteInterneRH, 'getFond', 'setFond');
    }

    #[Route(path: '/post/img/{entity}', name: 'api_attachment_postimg')]
    public function postImg(AttachmentRepository $repository, Post $entity, PostRequestHandler $postRequestHandler): Response
    {
        return $this->deleteFile($repository, $entity, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/profil/avatar', name: 'api_attachment_profilavatar')]
    public function profilAvatar(
        UserRequestHandler $userRequestHandler,
        AttachmentRepository $repository
    ): Response
    {
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->token->getToken();
        $entity = $token->getUser();
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $old        = clone $entity;
        $attachment = $entity->getAvatar();
        $this->deleteAttachment($repository, $attachment);
        $entity->setAvatar(null);
        $userRequestHandler->handle($old, $entity);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    #[Route(path: '/user/avatar/{entity}', name: 'api_attachment_useravatar')]
    public function userAvatar(AttachmentRepository $repository, User $entity, UserRequestHandler $userRequestHandler): Response
    {
        return $this->deleteFile($repository, $entity, $userRequestHandler, 'getAvatar', 'setAvatar');
    }

    protected function deleteAttachment(AttachmentRepository $repository, ?Attachment $attachment)
    {
        if (is_null($attachment)) {
            return;
        }

        $repository->remove($attachment);
    }

    protected function verifToken($entity): bool
    {
        $token = $this->requeststack->getCurrentRequest()->request->all('_token');

        $csrfToken = new CsrfToken(
            'attachment-img-'.$entity->getId(),
            $token
        );

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }

    private function deleteFile($repository, $entity, $requesthandler, $methodGet, $methodSet)
    {
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $old        = clone $entity;
        $attachment = call_user_func([$entity, $methodGet]);
        $this->deleteAttachment($repository, $attachment);
        call_user_func([$entity, $methodSet], null);
        $requesthandler->handle($old, $entity);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    private function setDataAttachment($repository, $method)
    {
        $entity = call_user_func([$repository, $method]);
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $this->deleteAttachment($repository, $entity);

        return new JsonResponse($return);
    }
}
