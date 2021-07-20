<?php

namespace Labstag\Controller;

use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/edito", name="edito")
     */
    public function edito(EditoRepository $editoRepository): Response
    {
        $edito = $editoRepository->findOnePublier();

        return $this->render(
            'front/edito.html.twig',
            ['edito' => $edito]
        );
    }

    /**
     * @Route("/", name="front")
     */
    public function index(EditoRepository $editoRepository, PostRepository $postRepository): Response
    {
        return $this->render(
            'front/index.html.twig',
            [
                'edito'    => $editoRepository->findOnePublier(),
                'posts'    => $postRepository->findPublier(),
                'archives' => $postRepository->findDateArchive(),
            ]
        );
    }
}
