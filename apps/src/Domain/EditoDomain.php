<?php

namespace Labstag\Domain;

use Labstag\Entity\Edito;
use Labstag\Form\Gestion\EditoType;

use Labstag\Form\Gestion\Search\EditoType as SearchEditoType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\EditoSearch;

class EditoDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Edito::class;
    }

    public function getSearchData(): EditoSearch
    {
        return new EditoSearch();
    }

    public function getSearchForm(): string
    {
        return SearchEditoType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/edito/index.html.twig',
            'trash'   => 'gestion/edito/index.html.twig',
            'edit'    => 'gestion/edito/form.html.twig',
            'show'    => 'gestion/edito/show.html.twig',
            'preview' => 'gestion/edito/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_edito_index'   => $this->translator->trans('edito.title', [], 'gestion.breadcrumb'),
            'gestion_edito_edit'    => $this->translator->trans('edito.edit', [], 'gestion.breadcrumb'),
            'gestion_edito_new'     => $this->translator->trans('edito.new', [], 'gestion.breadcrumb'),
            'gestion_edito_trash'   => $this->translator->trans('edito.trash', [], 'gestion.breadcrumb'),
            'gestion_edito_preview' => $this->translator->trans('edito.preview', [], 'gestion.breadcrumb'),
            'gestion_edito_show'    => $this->translator->trans('edito.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return EditoType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_edito_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_edito_index',
            'new'      => 'gestion_edito_new',
            'preview'  => 'gestion_edito_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_edito_show',
            'trash'    => 'gestion_edito_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
