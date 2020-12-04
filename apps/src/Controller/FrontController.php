<?php

namespace Labstag\Controller;

use Labstag\Lib\ControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends ControllerLib
{
    /**
     * @Route("/", name="front")
     */
    public function index(): Response
    {
        return $this->render(
            'front.html.twig',
            ['controller_name' => 'FrontController']
        );
    }
}
