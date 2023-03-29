<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
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
        ?Edito $edito
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($edito) ? new Edito() : $edito,
            'admin/edito/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/edito/index.html.twig',
        );
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
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(Edito $edito): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $edito,
            'admin/edito/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Edito::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
