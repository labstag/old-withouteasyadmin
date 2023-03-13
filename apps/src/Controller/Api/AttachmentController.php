<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Attachment;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\AttachmentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route(path: '/api/attachment', name: 'api_attachment_')]
class AttachmentController extends ApiControllerLib
{
    #[Route(path: '/delete/{attachment}', name: 'delete')]
    public function delete(
        AttachmentRepository $attachmentRepository,
        Attachment $attachment
    ): JsonResponse
    {
        $return = [
            'state' => false,
            'error' => '',
        ];
        $token = $this->verifToken($attachment);
        if (!$token) {
            $return['error'] = 'Token incorrect';

            return new JsonResponse($return);
        }

        $attachmentRepository->remove($attachment);
        $return['state'] = true;

        return new JsonResponse($return);
    }

    protected function verifToken(Attachment $attachment): bool
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $token   = (string) $request->request->get('_token');

        $csrfToken = new CsrfToken(
            (string) 'attachment-img-'.$attachment->getId(),
            $token
        );

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }
}
