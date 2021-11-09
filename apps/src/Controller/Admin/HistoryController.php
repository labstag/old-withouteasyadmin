<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\History;
use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapterRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Search\HistorySearch;
use Labstag\Service\AttachFormService;
use setasign\Fpdi\Fpdi;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/admin/history")
 */
class HistoryController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_history_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_history_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?History $history,
        HistoryRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            HistoryType::class,
            !is_null($history) ? $history : new History(),
            'admin/history/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_history_trash", methods={"GET"})
     * @Route("/",       name="admin_history_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(HistoryRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/history/index.html.twig',
        );
    }

    /**
     * @Route("/{id}/pdf", name="admin_history_pdf", methods={"GET"})
     */
    public function pdf(Environment $twig, History $history)
    {
        $files = [];
        foreach ($history->getChapters() as $chapter) {
            $tmpfile = tmpfile();
            $data    = stream_get_meta_data($tmpfile);
            $pdf     = new Html2Pdf();
            $html    = $twig->render(
                'pdf/history/content.html.twig',
                ['chapter' => $chapter]
            );
            $pdf->writeHTML($html);
            $file = $data['uri'].'.pdf';
            $pdf->output($file, 'F');
            $files[$data['uri'].'.pdf'] = [
                'file' => $file,
                'name' => $chapter->getName(),
            ];
        }

        $fpdi     = new Fpdi();
        $info     = [];
        $position = 1;
        foreach ($files as $row) {
            $position = $position + $fpdi->setSourceFile($row['file']);
            $info[]   = [
                'name'     => $row['name'],
                'file'     => $row['file'],
                'position' => $position,
            ];
        }

        $tmpfile = tmpfile();
        $data    = stream_get_meta_data($tmpfile);
        $pdf     = new Html2Pdf();
        $html    = $twig->render(
            'pdf/history/summary.html.twig',
            [
                'history' => $history,
                'info'    => $info,
            ]
        );
        $pdf->writeHTML($html);
        $file   = $data['uri'].'.pdf';
        $output = $pdf->output($file, 'F');
        array_unshift(
            $files,
            ['file' => $file]
        );
        $pdf = new Fpdi();
        foreach ($files as $row) {
            $pdf = $this->addPagePdf($pdf, $row['file']);
        }

        $pdf->output();
    }

    /**
     * @Route("/{id}/move", name="admin_history_move", methods={"GET", "POST"})
     */
    public function position(
        History $history,
        Request $request,
        ChapterRepository $repository
    )
    {
        $currentUrl = $this->generateUrl(
            'admin_history_move',
            [
                'id' => $history->getId(),
            ]
        );

        if ('POST' == $request->getMethod()) {
            $entityManager = $this->getDoctrine()->getManager();
            $data          = $request->request->get('position');
            if (!empty($data)) {
                $data = json_decode($data, true);
            }

            if (is_array($data)) {
                foreach ($data as $row) {
                    $id       = $row['id'];
                    $position = intval($row['position']);
                    $entity   = $repository->find($id);
                    if (!is_null($entity)) {
                        $entity->setPosition($position + 1);
                        $entityManager->persist($entity);
                    }
                }

                $entityManager->flush();
            }
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
     * @Route("/{id}",         name="admin_history_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_history_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        History $history
    ): Response
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

    protected function searchForm(): array
    {
        return [
            'form' => SearchHistoryType::class,
            'data' => new HistorySearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminHistory(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryMove(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.move', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_move',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('history.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('history.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminHistoryTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('history.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_history_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_history' => $this->translator->trans('history.title', [], 'admin.header'),
            ]
        );
    }

    private function addPagePdf($fpdi, $fichier)
    {
        $pageCount = $fpdi->setSourceFile($fichier);
        for ($pageNo = 1; $pageNo <= $pageCount; ++$pageNo) {
            $templateId = $fpdi->importPage($pageNo);
            $fpdi->addPage();
            $fpdi->useTemplate($templateId);
        }

        return $fpdi;
    }
}
