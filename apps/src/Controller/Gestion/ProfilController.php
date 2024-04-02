<?php

namespace Labstag\Controller\Gestion;

use Labstag\Entity\Profil;
use Labstag\Entity\User;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\ViewService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/profil')]
class ProfilController extends GestionControllerLib
{
    #[Route(path: '/', name: 'gestion_profil', methods: ['GET', 'POST'])]
    public function profil(
        Security $security
    ): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        return $this->setAdmin()->edit($user);
    }

    protected function setAdmin(): ViewService
    {
        return $this->gestionService->setDomain(Profil::class);
    }
}
