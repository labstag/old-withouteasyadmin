<?php

namespace Labstag\Controller\Api;

use Labstag\Repository\UserRepository;
use Labstag\Service\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api/attachment")
 */
class AttachmentController extends AbstractController
{

    protected PhoneService $phoneService;

    public function __construct(PhoneService $phoneService)
    {
        $this->phoneService = $phoneService;
    }

    /**
     * @Route("/profil/avatar", name="api_attachment_profilavatar")
     *
     * @return Response
     */
    public function profilAvatar(): JsonResponse
    {
        return new JsonResponse(['profilavatar']);
    }

    /**
     * @Route("/user/avatar/{entity}", name="api_attachment_useravatar")
     *
     * @return Response
     */
    public function userAvatar(): JsonResponse
    {
        return new JsonResponse(['useravatar']);
    }

    /**
     * @Route("/edito/fond/{entity}", name="api_attachment_editofond")
     *
     * @return Response
     */
    public function editoFond(): JsonResponse
    {
        return new JsonResponse(['editofond']);
    }

    /**
     * @Route("/noteinterne/fond/{entity}", name="api_attachment_noteinterneofond")
     *
     * @return Response
     */
    public function noteinterneFond(): JsonResponse
    {
        return new JsonResponse(['noteinterne']);
    }
}
