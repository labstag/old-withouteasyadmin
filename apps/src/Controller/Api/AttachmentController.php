<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Attachment;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Edito;
use Labstag\Entity\Memo;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
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
    public function bookmarkImg(Bookmark $entity, PostRequestHandler $postRequestHandler): Response
    {
        return $this->deleteFile($entity, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/edito/fond/{entity}', name: 'api_attachment_editofond')]
    public function editoFond(Edito $entity, EditoRequestHandler $editoRH): Response
    {
        return $this->deleteFile($entity, $editoRH, 'getFond', 'setFond');
    }

    #[Route(path: '/favicon', name: 'api_attachment_favicon')]
    public function favicon(): Response
    {
        return $this->setDataAttachment('getFavicon');
    }

    #[Route(path: '/imagedefault', name: 'api_attachment_image')]
    public function imageDefault(): Response
    {
        return $this->setDataAttachment('getImageDefault');
    }

    #[Route(path: '/memo/fond/{entity}', name: 'api_attachment_memofond')]
    public function memoFond(Memo $entity, MemoRequestHandler $noteInterneRH): Response
    {
        return $this->deleteFile($entity, $noteInterneRH, 'getFond', 'setFond');
    }

    #[Route(path: '/post/img/{entity}', name: 'api_attachment_postimg')]
    public function postImg(Post $entity, PostRequestHandler $postRequestHandler): Response
    {
        return $this->deleteFile($entity, $postRequestHandler, 'getImg', 'setImg');
    }

    #[Route(path: '/profil/avatar', name: 'api_attachment_profilavatar')]
    public function profilAvatar(UserRequestHandler $userRequestHandler): Response
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
        $this->deleteAttachment($attachment);
        $entity->setAvatar(null);
        $userRequestHandler->handle($old, $entity);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    #[Route(path: '/user/avatar/{entity}', name: 'api_attachment_useravatar')]
    public function userAvatar(User $entity, UserRequestHandler $userRequestHandler): Response
    {
        return $this->deleteFile($entity, $userRequestHandler, 'getAvatar', 'setAvatar');
    }

    protected function deleteAttachment(?Attachment $attachment)
    {
        $repository = $this->getRepository(Attachment::class);
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

    private function deleteFile($entity, $requesthandler, $methodGet, $methodSet)
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
        $this->deleteAttachment($attachment);
        call_user_func([$entity, $methodSet], null);
        $requesthandler->handle($old, $entity);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    private function setDataAttachment($method)
    {
        $entity = call_user_func([$this->getRepository(Attachment::class), $method]);
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $this->deleteAttachment($entity);

        return new JsonResponse($return);
    }
}
