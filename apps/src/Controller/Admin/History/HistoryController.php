<?php

namespace Labstag\Controller\Admin\History;

use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Service\HistoryService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history')]
class HistoryController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_history_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?History $history
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($history) ? new History() : $history,
            'admin/history/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_history_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_history_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/history/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_history_new', methods: ['GET', 'POST'])]
    public function new(
        HistoryRepository $historyRepository,
        HistoryRequestHandler $historyRequestHandler,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();

        $history = new History();
        $history->setPublished(new DateTime());
        $history->setName(Uuid::v1());
        $history->setRefuser($user);

        $old = clone $history;
        $historyRepository->add($history);
        $historyRequestHandler->handle($old, $history);

        return $this->redirectToRoute('admin_history_edit', ['id' => $history->getId()]);
    }

    #[Route(path: '/{id}/pdf', name: 'admin_history_pdf', methods: ['GET'])]
    public function pdf(HistoryService $historyService, History $history): RedirectResponse
    {
        $historyService->process(
            $this->getParameter('file_directory'),
            $history->getId(),
            true
        );
        $filename = $historyService->getFilename();
        if (empty($filename)) {
            throw $this->createNotFoundException('Pas de fichier');
        }

        $filename = str_replace(
            $this->getParameter('kernel.project_dir').'/public/',
            '/',
            $filename
        );

        return $this->redirect($filename);
    }

    #[Route(path: '/{id}/move', name: 'admin_history_move', methods: ['GET', 'POST'])]
    public function position(History $history, Request $request): Response
    {
        $currentUrl = $this->generateUrl(
            'admin_history_move',
            [
                'id' => $history->getId(),
            ]
        );
        if ('POST' == $request->getMethod()) {
            $this->setPositionEntity($request, Chapter::class);
        }

        $this->btnInstance()->addBtnList(
            'admin_history_index',
            'Liste',
        );
        $this->btnInstance()->add(
            'btn-admin-save-move',
            'Enregistrer',
            [
                'is'   => 'link-btnadminmove',
                'href' => $currentUrl,
            ]
        );

        return $this->render(
            'admin/history/move.html.twig',
            ['history' => $history]
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_history_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_history_preview', methods: ['GET'])]
    public function showOrPreview(History $history): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $history,
            'admin/history/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(History::class);
    }
}
