<?php

namespace Labstag\Lib;

use Labstag\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AdminControllerLib extends AbstractController
{
    public function __construct(
        protected AdminService $adminService
    ) {
    }
}
