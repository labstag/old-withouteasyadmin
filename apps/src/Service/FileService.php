<?php

namespace Labstag\Service;

use Labstag\Entity\Attachment;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public function __construct(
        protected ContainerBagInterface $containerBag,
        private readonly AttachmentRequestHandler $attachmentRequestHandler
    )
    {
    }

    public function moveFile(
        UploadedFile $uploadedFile,
        string $path,
        string $filename,
        ?Attachment $attachment,
        ?Attachment $old
    ): void
    {
        $uploadedFile->move(
            $path,
            $filename
        );
        $uploadedFile = $path.'/'.$filename;

        $this->setAttachment($uploadedFile, $attachment, $old);
    }

    public function setAttachment(
        string $file,
        ?Attachment $attachment = null,
        ?Attachment $old = null
    ): Attachment
    {
        if (is_null($attachment) && is_null($old)) {
            $attachment = new Attachment();
            $old        = clone $attachment;
        }

        /** @var Attachment $attachment */
        /** @var Attachment $old */
        $attachment->setMimeType((string) mime_content_type($file));
        $attachment->setSize((int) filesize($file));
        $attachment->setName(
            str_replace(
                $this->containerBag->get('kernel.project_dir').'/public/',
                '',
                (string) $file
            )
        );
        $this->attachmentRequestHandler->handle($old, $attachment);

        return $attachment;
    }
}
