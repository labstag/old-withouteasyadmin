<?php

namespace Labstag\Lib;

use Labstag\Service\GestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class GestionControllerLib extends AbstractController
{
    public function __construct(
        protected GestionService $gestionService
    )
    {
    }
}
