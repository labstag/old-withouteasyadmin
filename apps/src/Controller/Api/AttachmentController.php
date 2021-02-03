<?php

namespace Labstag\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Attachment;
use Labstag\Entity\Edito;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\NoteInterneRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @Route("/api/attachment")
 */
class AttachmentController extends AbstractController
{

    protected PhoneService $phoneService;

    protected RequestStack $requestStack;

    protected Request $request;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected TokenStorageInterface $token;

    protected AttachmentRequestHandler $attachmentRH;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        RequestStack $requestStack,
        CsrfTokenManagerInterface $csrfTokenManager,
        TokenStorageInterface $token,
        PhoneService $phoneService,
        EntityManagerInterface $entityManager,
        AttachmentRequestHandler $attachmentRH
    )
    {
        $this->attachmentRH     = $attachmentRH;
        $this->token            = $token;
        $this->requestStack     = $requestStack;
        $this->entityManager    = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        /** @var Request $request */
        $request            = $this->requestStack->getCurrentRequest();
        $this->request      = $request;
        $this->phoneService = $phoneService;
    }

    /**
     * @Route("/profil/avatar", name="api_attachment_profilavatar")
     *
     * @return Response
     */
    public function profilAvatar(UserRequestHandler $userRequestHandler): JsonResponse
    {
        $return = [
            'state'   => false,
            'message' => '',
        ];
        $token  = $this->token->getToken();
        $entity = $token->getUser();
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['message'] = 'Token incorrect';
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

    /**
     * @Route("/user/avatar/{entity}", name="api_attachment_useravatar")
     *
     * @return Response
     */
    public function userAvatar(User $entity, UserRequestHandler $userRequestHandler): JsonResponse
    {
        $return = [
            'state'   => false,
            'message' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['message'] = 'Token incorrect';
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

    /**
     * @Route("/edito/fond/{entity}", name="api_attachment_editofond")
     *
     * @return Response
     */
    public function editoFond(Edito $entity, EditoRequestHandler $editoRH): JsonResponse
    {
        $return = [
            'state'   => false,
            'message' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['message'] = 'Token incorrect';
            return new JsonResponse($return);
        }

        $old        = clone $entity;
        $attachment = $entity->getFond();
        $this->deleteAttachment($attachment);
        $entity->setFond(null);
        $editoRH->handle($old, $entity);
        $return['state'] = true;
        return new JsonResponse($return);
    }

    /**
     * @Route("/noteinterne/fond/{entity}", name="api_attachment_noteinternefond")
     *
     * @return Response
     */
    public function noteinterneFond(NoteInterne $entity, NoteInterneRequestHandler $noteInterneRH): JsonResponse
    {
        $return = [
            'state'   => false,
            'message' => '',
        ];
        $token  = $this->verifToken($entity);
        if (!$token) {
            $return['message'] = 'Token incorrect';
            return new JsonResponse($return);
        }

        $old        = clone $entity;
        $attachment = $entity->getFond();
        $this->deleteAttachment($attachment);
        $entity->setFond(null);
        $noteInterneRH->handle($old, $entity);
        $return['state'] = true;
        return new JsonResponse($return);
    }

    protected function deleteAttachment(?Attachment $attachment)
    {
        if (is_null($attachment)) {
            return;
        }

        $this->entityManager->remove($attachment);
        $this->entityManager->flush();
    }

    protected function verifToken($entity): bool
    {
        $token = $this->request->request->get('_token');

        $csrfToken = new CsrfToken(
            'attachment-img-' . $entity->getId(),
            $token
        );
        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }
}
