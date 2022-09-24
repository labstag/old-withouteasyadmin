<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Libelle;
use Labstag\Form\Admin\Search\LibelleType as SearchLibelleType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\Search\LibelleSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/libelle')]
class LibelleController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_libelle_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_libelle_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Libelle $libelle,
        LibelleRequestHandler $libelleRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
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

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Libelle::class);
    }

    /**
     * @return array<string, \LibelleSearch>|array<string, class-string<\Labstag\Form\Admin\Search\LibelleType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchLibelleType::class,
            'data' => new LibelleSearch(),
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

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [
            ...$headers, ...
            [
                'admin_libelle' => $this->translator->trans('libelle.title', [], 'admin.header'),
            ],
        ];
    }
}
