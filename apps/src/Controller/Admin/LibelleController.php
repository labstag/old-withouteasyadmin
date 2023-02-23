<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Libelle;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/libelle')]
class LibelleController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_libelle_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_libelle_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Libelle $libelle
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($libelle) ? new Libelle() : $libelle
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_libelle_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_libelle_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/libelle/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_libelle_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_libelle_preview', methods: ['GET'])]
    public function showOrPreview(Libelle $libelle): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $libelle,
            'admin/libelle/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        return $this->domainService->getDomain(Libelle::class);
    }
}
