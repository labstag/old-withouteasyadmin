<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Lib\AdminControllerLib;
use Labstag\Service\Admin\Entity\GuardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/guard')]
class GuardController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_guard_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $viewService = $this->adminService->setDomain('guard');
        if (!$viewService instanceof GuardService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->global();
    }
}
