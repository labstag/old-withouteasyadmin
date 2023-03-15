<?php

namespace Labstag\Controller\Admin\History;

use DateTime;
use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Service\HistoryService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history', name: 'admin_history_')]
class HistoryController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/history/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        HistoryRepository $historyRepository,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('admin_history_index');
        }

        $history = new History();
        $history->setPublished(new DateTime());
        $history->setName(Uuid::v1());
        $history->setRefuser($user);

        $historyRepository->add($history);

        return $this->redirectToRoute('admin_history_edit', ['id' => $history->getId()]);
    }

    #[Route(path: '/{id}/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(HistoryService $historyService, History $history): RedirectResponse
    {
        $fileDirectory    = $this->getParameter('file_directory');
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        if (!is_string($fileDirectory) || !is_string($kernelProjectDir)) {
            throw $this->createNotFoundException('Pas de fichier');
        }

        $historyService->process(
            (string) $fileDirectory,
            (string) $history->getId(),
            true
        );
        $filename = $historyService->getFilename();
        if (empty($filename)) {
            throw $this->createNotFoundException('Pas de fichier');
        }

        $filename = str_replace(
            ((string) $kernelProjectDir).'/public/',
            '/',
            $filename
        );

        return $this->redirect($filename);
    }

    #[Route(path: '/{id}/move', name: 'move', methods: ['GET', 'POST'])]
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

        $this->adminBtnService->addBtnList(
            'admin_history_index',
            'Liste',
        );
        $this->adminBtnService->add(
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

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(History $history): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $history,
            'admin/history/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(History::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
