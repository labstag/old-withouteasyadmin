<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Profil;
use Labstag\Lib\AdminControllerLib;
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
        return $this->form(
            $this->getDomainEntity(),
            $security->getUser(),
            'admin/profil.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Profil::class);
    }
}
