<?php

namespace Labstag\Controller\Admin\History;

use Symfony\Component\HttpFoundation\RedirectResponse;
use DateTime;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Search\HistorySearch;
use Labstag\Service\AttachFormService;
use Labstag\Service\HistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history')]
class HistoryController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_history_edit', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $attachFormService, ?History $history, HistoryRequestHandler $historyRequestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $attachFormService,
            $historyRequestHandler,
            HistoryType::class,
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
            History::class,
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
            $history,
            'admin/history/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'move'     => 'admin_history_move',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_history_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_history_index',
            'new'      => 'admin_history_new',
            'preview'  => 'admin_history_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_history_show',
            'trash'    => 'admin_history_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    /**
     * @return array<string, class-string<\Labstag\Form\Admin\Search\HistoryType>>|array<string, \HistorySearch>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchHistoryType::class,
            'data' => new HistorySearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('history.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_index',
                ],
                [
                    'title' => $this->translator->trans('history.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_edit',
                ],
                [
                    'title' => $this->translator->trans('history.move', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_move',
                ],
                [
                    'title' => $this->translator->trans('history.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_new',
                ],
                [
                    'title' => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_trash',
                ],
                [
                    'title' => $this->translator->trans('history.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_preview',
                ],
                [
                    'title' => $this->translator->trans('history.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_history_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [...$headers, ...[
            'admin_history' => $this->translator->trans('history.title', [], 'admin.header'),
        ]];
    }
}
