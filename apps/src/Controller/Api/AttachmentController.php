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

    #[Route(path: '/delete/{entity}', name: 'api_attachment_delete')]
    public function delete(
        AttachmentRepository $attachmentRepository,
        Attachment $attachment,
        MemoRequestHandler $memoRequestHandler
    ): JsonResponse
    {
        
        $token  = $this->verifToken($attachment);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }
        $this->deleteAttachment($attachmentRepository, $attachment);
        $return['state'] = true;

        return new JsonResponse($return);
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
}
