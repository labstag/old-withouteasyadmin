<?php

namespace Labstag\Controller;

use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    #[Route(path: '/edito', name: 'edito', priority: 1)]
    public function edio(
        EditoRepository $editoRepo
    )
    {
        $edito = $editoRepo->findOnePublier();

        if (!$edito instanceof Edito) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'front.html.twig',
            ['content' => $edito]
        );
    }

    #[Route(
        path: '/mes-histoires/{slug}',
        name: 'front_history',
        requirements: ['slug' => '.+'],
        priority: 1
    )]
    public function history(
        string $slug,
        HistoryRepository $historyRepo,
        PageRepository $pageRepo
    )
    {
        $history = $historyRepo->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            return $this->page('mes-histoires/'.$slug, $pageRepo);
        }

        return $this->render(
            'front.html.twig',
            ['content' => $history]
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
        PageRepository $pageRepo
    )
    {
        return $this->page($slug, $pageRepo);
    }
}
