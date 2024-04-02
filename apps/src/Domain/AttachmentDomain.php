<?php

namespace Labstag\Domain;

use Labstag\Entity\Attachment;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\AttachmentSearch;

class AttachmentDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Attachment::class;
    }

    public function getSearchData(): AttachmentSearch
    {
        return new AttachmentSearch();
    }

    public function getTemplates(): array
    {
        return [
            'index' => 'gestion/attachment/index.html.twig',
            'trash' => 'gestion/attachment/index.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_attachment_index' => $this->translator->trans('attachment.title', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return '';
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_attachment_index',
            'restore'  => 'api_action_restore',
            'trash'    => 'gestion_attachment_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
