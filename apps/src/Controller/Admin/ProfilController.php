<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Profil;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(path: '/admin/profil')]
class ProfilController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_profil', methods: ['GET', 'POST'])]
    public function profil(
        Security $security
    ): Response
    {
        $this->modalAttachmentDelete();

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
