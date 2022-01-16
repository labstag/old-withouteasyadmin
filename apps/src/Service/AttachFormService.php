<?php

namespace Labstag\Service;

use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;

class AttachFormService
{

    public function __construct(private UploadAnnotationReader $uploadAnnotReader, private AttachmentRepository $attachmentRepository, private AttachmentRequestHandler $attachmentRH)
    {
    }

    public function getRepository(): AttachmentRepository
    {
        return $this->attachmentRepository;
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
