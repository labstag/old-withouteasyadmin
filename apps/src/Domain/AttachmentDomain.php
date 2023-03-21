<?php

namespace Labstag\Domain;

use Labstag\Entity\Attachment;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\Search\AttachmentSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttachmentDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected AttachmentRepository $attachmentRepository,
        protected AttachmentSearch $attachmentSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Attachment::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->attachmentRepository;
    }

    public function getSearchData(): AttachmentSearch
    {
        return $this->attachmentSearch;
    }

    public function getTitles(): array
    {
        return [
            'admin_attachment_index' => $this->translator->trans('attachment.title', [], 'admin.breadcrumb'),
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
            'list'     => 'admin_attachment_index',
            'restore'  => 'api_action_restore',
            'trash'    => 'admin_attachment_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
