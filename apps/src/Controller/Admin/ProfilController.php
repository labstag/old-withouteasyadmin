<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Profil;
use Labstag\Entity\User;
use Labstag\Lib\AdminControllerLib;
use Labstag\Service\AdminService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/profil')]
class ProfilController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_profil', methods: ['GET', 'POST'])]
    public function profil(
        Security $security
    ): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        return $this->setAdmin()->edit($user);
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Profil::class);

        return $this->adminService;
    }
}
