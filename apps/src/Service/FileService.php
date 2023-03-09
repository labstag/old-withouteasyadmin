<?php

namespace Labstag\Service;

use Labstag\Entity\Attachment;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class FileService
{
    public function __construct(
        protected ContainerBagInterface $containerBag,
        private readonly AttachmentRequestHandler $attachmentRequestHandler
    )
    {
    }

    public function moveFile(
        mixed $file,
        string $path,
        string $filename,
        ?Attachment $attachment,
        ?Attachment $old
    ): void
    {
        $file->move(
            $path,
            $filename
        );
        $file = $path.'/'.$filename;

        $this->setAttachment($file, $attachment, $old);
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
