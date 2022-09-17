<?php

namespace Labstag\Controller;

use Labstag\Entity\Edito;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\PageRepository;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    #[Route(path: '/edito', name: 'front_edito', priority: 1)]
    public function edio(
        EditoRepository $editoRepository
    )
    {
        $edito = $editoRepository->findOnePublier();

        if (!$edito instanceof Edito) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'front.html.twig',
            ['content' => $edito]
        );
    }

    #[Route(
        path: '/{slug}{_</(?!/)>}',
        name: 'front',
        requirements: ['slug' => '.+'],
        defaults: [
            'slug' => '',
            '_'    => '',
        ]
    )]
    public function index(
        string $slug,
        PageRepository $pageRepository
    )
    {
        return $this->page($slug, $pageRepository);
    }
}
