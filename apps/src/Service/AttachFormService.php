<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\RequestHandler\AttachmentRequestHandler;

class AttachFormService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private UploadAnnotationReader $uploadAnnotReader,
        private AttachmentRequestHandler $attachmentRH
    )
    {
    }

    public function getRequestHandler(): AttachmentRequestHandler
    {
        return $this->attachmentRH;
    }

    public function getUploadAnnotationReader(): UploadAnnotationReader
    {
        return $this->uploadAnnotReader;
    }
}
