<?php

namespace Labstag\Domain;

use Labstag\Entity\Libelle;
use Labstag\Form\Admin\LibelleType;

use Labstag\Form\Admin\Search\LibelleType as SearchLibelleType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\LibelleRepository;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\Search\LibelleSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class LibelleDomain extends DomainLib
{
    public function __construct(
        protected LibelleRequestHandler $libelleRequestHandler,
        protected LibelleRepository $libelleRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Libelle::class;
    }

    public function getRepository()
    {
        return $this->libelleRepository;
    }

    public function getRequestHandler()
    {
        return $this->libelleRequestHandler;
    }

    public function getSearchData()
    {
        return LibelleSearch::class;
    }

    public function getSearchForm()
    {
        return SearchLibelleType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_libelle_index'   => $this->translator->trans('libelle.title', [], 'admin.breadcrumb'),
            'admin_libelle_edit'    => $this->translator->trans('libelle.edit', [], 'admin.breadcrumb'),
            'admin_libelle_new'     => $this->translator->trans('libelle.new', [], 'admin.breadcrumb'),
            'admin_libelle_trash'   => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
            'admin_libelle_preview' => $this->translator->trans('libelle.preview', [], 'admin.breadcrumb'),
            'admin_libelle_show'    => $this->translator->trans('libelle.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return LibelleType::class;
    }

    public function getUrlAdmin(): array
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
}
