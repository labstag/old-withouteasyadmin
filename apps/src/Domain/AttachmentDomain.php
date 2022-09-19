<?php

namespace Labstag\Domain;

use Labstag\Entity\Attachment;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\AttachmentRequestHandler;

class AttachmentDomain extends DomainLib
{
    public function __construct(
        protected AttachmentRequestHandler $attachmentRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Attachment::class;
    }

    public function getRequestHandler()
    {
        return $this->attachmentRequestHandler;
    }
}
