<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/edito')]
class EditoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_edito_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Edito $edito
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $this->getDomainEntity(),
            is_null($edito) ? new Edito() : $edito,
            'admin/edito/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_edito_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_edito_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/edito/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_edito_new', methods: ['GET', 'POST'])]
    public function new(
        EditoRepository $editoRepository,
        EditoRequestHandler $editoRequestHandler,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();

        $edito = new Edito();
        $edito->setPublished(new DateTime());
        $edito->setTitle(Uuid::v1());
        $edito->setRefuser($user);

        $old = clone $edito;
        $editoRepository->add($edito);
        $editoRequestHandler->handle($old, $edito);

        return $this->redirectToRoute('admin_edito_edit', ['id' => $edito->getId()]);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_edito_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_edito_preview', methods: ['GET'])]
    public function showOrPreview(Edito $edito): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $edito,
            'admin/edito/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Edito::class);
    }
}
