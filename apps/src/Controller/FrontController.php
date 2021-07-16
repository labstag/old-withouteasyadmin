<?php

namespace Labstag\Controller;

use Labstag\Lib\FrontControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/edito", name="edito")
     */
    public function edito(): Response
    {
        $edito = $this->editoData();

        return $this->render(
            'front/edito.html.twig',
            ['edito' => $edito]
        );
    }

    /**
     * @Route("/", name="front")
     */
    public function index(): Response
    {
        $edito = $this->editoData();
        $posts = $this->postData();

        return $this->render(
            'front/index.html.twig',
            [
                'edito' => $edito,
                'posts' => $posts,
            ]
        );
    }
}
