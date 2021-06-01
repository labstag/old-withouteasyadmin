<?php

namespace Labstag\Lib;

use Labstag\Repository\EditoRepository;
use Labstag\Service\DataService;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class FrontControllerLib extends ControllerLib
{

    protected DataService $dataService;

    protected Breadcrumbs $breadcrumbs;

    protected EditoRepository $editoRepository;

    public function __construct(
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        EditoRepository $editoRepository
    )
    {
        $this->editoRepository = $editoRepository;
        parent::__construct($dataService, $breadcrumbs);
    }

    protected function editoData()
    {
        $edito = $this->editoRepository->findOnePublier();

        return $edito;
    }
}
