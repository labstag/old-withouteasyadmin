<?php

namespace Labstag\Domain;

use Labstag\Entity\Attachment;

use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Symfony\Contracts\Translation\TranslatorInterface;

class AttachmentDomain extends DomainLib
{
    public function __construct(
        protected AttachmentRequestHandler $attachmentRequestHandler,
        protected AttachmentRepository $attachmentRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Attachment::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->attachmentRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->attachmentRequestHandler;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_attachment_index' => $this->translator->trans('attachment.title', [], 'admin.breadcrumb'),
        ];
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
