<?php

namespace Labstag\Controller\Api;

use Labstag\Service\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api")
 */
class CheckPhoneController extends AbstractController
{

    private PhoneService $phoneService;

    public function __construct(PhoneService $phoneService)
    {
        $this->phoneService = $phoneService;
    }

    /**
     * @Route("/checkphone", name="api_checkphone")
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('country', $get) || !array_key_exists('phone', $get)) {
            return $this->json($return);
        }

        $verif             = $this->phoneService->verif($get['phone'], $get['country']);
        $return['isvalid'] = array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;

        return $this->json($return);
    }
}
