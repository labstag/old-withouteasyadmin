<?php

namespace Labstag\Domain;

use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;

use Labstag\Form\Admin\Search\EditoType as SearchEditoType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\Search\EditoSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class EditoDomain extends DomainLib
{
    public function __construct(
        protected EditoRequestHandler $editoRequestHandler,
        protected EditoRepository $editoRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Edito::class;
    }

    public function getRepository()
    {
        return $this->editoRepository;
    }

    public function getRequestHandler()
    {
        return $this->editoRequestHandler;
    }

    public function getSearchData()
    {
        return new EditoSearch();
    }

    public function getSearchForm()
    {
        return SearchEditoType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_edito_index'   => $this->translator->trans('edito.title', [], 'admin.breadcrumb'),
            'admin_edito_edit'    => $this->translator->trans('edito.edit', [], 'admin.breadcrumb'),
            'admin_edito_new'     => $this->translator->trans('edito.new', [], 'admin.breadcrumb'),
            'admin_edito_trash'   => $this->translator->trans('edito.trash', [], 'admin.breadcrumb'),
            'admin_edito_preview' => $this->translator->trans('edito.preview', [], 'admin.breadcrumb'),
            'admin_edito_show'    => $this->translator->trans('edito.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return EditoType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_edito_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_edito_index',
            'new'      => 'admin_edito_new',
            'preview'  => 'admin_edito_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_edito_show',
            'trash'    => 'admin_edito_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
