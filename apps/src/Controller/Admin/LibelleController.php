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

/**
 * @Route("/admin/libelle")
 */
class LibelleController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_libelle_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_libelle_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Libelle $libelle,
        LibelleRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            LibelleType::class,
            !is_null($libelle) ? $libelle : new Libelle()
        );
    }

    /**
     * @Route("/trash", name="admin_libelle_trash", methods={"GET"})
     * @Route("/", name="admin_libelle_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Libelle::class,
            'admin/libelle/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_libelle_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_libelle_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Libelle $libelle
    ): Response
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

    protected function setBreadcrumbsPageAdminlibelle(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.title', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.new', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibellePreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_trash',
            ],
            [
                'title' => $this->translator->trans('libelle.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.show', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_libelle_trash',
            ],
        ];
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
