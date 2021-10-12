<?php

namespace Labstag\Service;

use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;

class AttachFormService
{

    private AttachmentRepository $attachmentRepository;

    private AttachmentRequestHandler $attachmentRH;

    private UploadAnnotationReader $uploadAnnotReader;

    public function __construct(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH
    )
    {
        $this->uploadAnnotReader    = $uploadAnnotReader;
        $this->attachmentRepository = $attachmentRepository;
        $this->attachmentRH         = $attachmentRH;
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
