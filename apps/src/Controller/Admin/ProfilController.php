<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Entity\Profil;
use Labstag\Entity\User;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/profil')]
class ProfilController extends AdminControllerLib
{
    #[Route(path: '/', name: 'admin_profil', methods: ['GET', 'POST'])]
    public function profil(
        Security $security
    ): Response {
        /** @var User $user */
        $user = $security->getUser();

        return $this->form(
            $this->getDomainEntity(),
            $user,
            'admin/profil.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Profil::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
