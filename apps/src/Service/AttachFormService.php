<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\RequestHandler\AttachmentRequestHandler;

class AttachFormService
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        private readonly UploadAnnotationReader $uploadAnnotationReader,
        private readonly AttachmentRequestHandler $attachmentRequestHandler
    )
    {
    }

    public function getRequestHandler(): AttachmentRequestHandler
    {
        return $this->attachmentRequestHandler;
    }

    public function getUploadAnnotationReader(): UploadAnnotationReader
    {
        return $this->uploadAnnotationReader;
    }
}
