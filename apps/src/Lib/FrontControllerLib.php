<?php

namespace Labstag\Lib;

use Labstag\Repository\EditoRepository;
use Labstag\Repository\PostRepository;
use Labstag\Service\DataService;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class FrontControllerLib extends ControllerLib
{

    protected Breadcrumbs $breadcrumbs;

    protected DataService $dataService;

    protected EditoRepository $editoRepository;

    protected PostRepository $postRepository;

    public function __construct(
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        EditoRepository $editoRepository,
        PostRepository $postRepository
    )
    {
        $this->editoRepository = $editoRepository;
        $this->postRepository = $postRepository;
        parent::__construct($dataService, $breadcrumbs);
    }

    protected function editoData()
    {
        $edito = $this->editoRepository->findOnePublier();

        return $edito;
    }

    protected function postData()
    {
        $posts = $this->postRepository->findPublier();

        return $posts;
    }
}
