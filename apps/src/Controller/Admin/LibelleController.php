<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Libelle;
use Labstag\Form\Admin\LibelleType;
use Labstag\Form\Admin\Search\LibelleType as SearchLibelleType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\Search\LibelleSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/libelle')]
class LibelleController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_libelle_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_libelle_new', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, ?Libelle $libelle, LibelleRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            LibelleType::class,
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
            Libelle::class,
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
            $libelle,
            'admin/libelle/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_libelle_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_libelle_index',
            'new'      => 'admin_libelle_new',
            'preview'  => 'admin_libelle_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_libelle_show',
            'trash'    => 'admin_libelle_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchLibelleType::class,
            'data' => new LibelleSearch(),
        ];
    }

    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('libelle.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_index',
                ],
                [
                    'title' => $this->translator->trans('libelle.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_edit',
                ],
                [
                    'title' => $this->translator->trans('libelle.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_new',
                ],
                [
                    'title' => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_trash',
                ],
                [
                    'title' => $this->translator->trans('libelle.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_preview',
                ],
                [
                    'title' => $this->translator->trans('libelle.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_libelle_show',
                ],
            ]
        );
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_libelle' => $this->translator->trans('libelle.title', [], 'admin.header'),
            ]
        );
    }
}
