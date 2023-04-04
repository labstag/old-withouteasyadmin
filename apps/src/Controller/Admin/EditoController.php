<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\Service\AdminService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/edito', name: 'admin_edito_')]
class EditoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Edito $edito
    ): Response
    {
        return $this->setAdmin()->edit($edito);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        EditoRepository $editoRepository,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('admin_edito_index');
        }

        $edito = new Edito();
        $edito->setPublished(new DateTime());
        $edito->setTitle(Uuid::v1());
        $edito->setRefuser($user);

        $editoRepository->save($edito);

        return $this->redirectToRoute('admin_edito_edit', ['id' => $edito->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Edito $edito): Response
    {
        return $this->setAdmin()->preview($edito);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Edito $edito): Response
    {
        return $this->setAdmin()->show($edito);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Edito::class);

        return $this->adminService;
    }
}
