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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/attachment')]
class AttachmentController extends ApiControllerLib
{
    #[Route(path: '/bookmark/img/{entity}', name: 'api_attachment_bookmarkimg')]
    public function bookmarkImg(
        AttachmentRepository $attachmentRepository,
        Bookmark $bookmark,
        PostRequestHandler $postRequestHandler
    ): JsonResponse
    {
        return $this->deleteFile($attachmentRepository, $bookmark, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/edito/fond/{entity}', name: 'api_attachment_editofond')]
    public function editoFond(
        AttachmentRepository $attachmentRepository,
        Edito $edito,
        EditoRequestHandler $editoRequestHandler
    ): JsonResponse
    {
        return $this->deleteFile($attachmentRepository, $edito, $editoRequestHandler, 'getFond', 'setFond');
    }

    #[Route(path: '/favicon', name: 'api_attachment_favicon')]
    public function favicon(
        AttachmentRepository $attachmentRepository
    ): JsonResponse
    {
        return $this->setDataAttachment($attachmentRepository, 'getFavicon');
    }

    #[Route(path: '/imagedefault', name: 'api_attachment_image')]
    public function imageDefault(
        AttachmentRepository $attachmentRepository
    ): JsonResponse
    {
        return $this->setDataAttachment($attachmentRepository, 'getImageDefault');
    }

    #[Route(path: '/memo/fond/{entity}', name: 'api_attachment_memofond')]
    public function memoFond(
        AttachmentRepository $attachmentRepository,
        Memo $memo,
        MemoRequestHandler $memoRequestHandler
    ): JsonResponse
    {
        return $this->deleteFile($attachmentRepository, $memo, $memoRequestHandler, 'getFond', 'setFond');
    }

    #[Route(path: '/post/img/{entity}', name: 'api_attachment_postimg')]
    public function postImg(
        AttachmentRepository $attachmentRepository,
        Post $post,
        PostRequestHandler $postRequestHandler
    ): JsonResponse
    {
        return $this->deleteFile($attachmentRepository, $post, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/profil/avatar', name: 'api_attachment_profilavatar')]
    public function profilAvatar(
        UserRequestHandler $userRequestHandler,
        AttachmentRepository $attachmentRepository
    ): JsonResponse
    {
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->tokenStorage->getToken();
        $user   = $token->getUser();
        $token  = $this->verifToken($user);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $old        = clone $user;
        $attachment = $user->getAvatar();
        $this->deleteAttachment($attachmentRepository, $attachment);
        $user->setAvatar(null);
        $userRequestHandler->handle($old, $user);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    #[Route(path: '/user/avatar/{entity}', name: 'api_attachment_useravatar')]
    public function userAvatar(
        AttachmentRepository $attachmentRepository,
        User $user,
        UserRequestHandler $userRequestHandler
    ): JsonResponse
    {
        return $this->deleteFile($attachmentRepository, $user, $userRequestHandler, 'getAvatar', 'setAvatar');
    }

    protected function deleteAttachment(AttachmentRepository $attachmentRepository, ?Attachment $attachment): void
    {
        if (is_null($attachment)) {
            return;
        }

        $attachmentRepository->remove($attachment);
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

    private function deleteFile(
        AttachmentRepository $attachmentRepository,
        $entity,
        $requesthandler,
        $methodGet,
        $methodSet
    ): JsonResponse
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
        $this->deleteAttachment($attachmentRepository, $attachment);
        call_user_func([$entity, $methodSet], null);
        $requesthandler->handle($old, $entity);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    private function setDataAttachment(AttachmentRepository $attachmentRepository, $method): JsonResponse
    {
        $entity = call_user_func([$attachmentRepository, $method]);
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $this->deleteAttachment($attachmentRepository, $entity);

        return new JsonResponse($return);
    }
}
