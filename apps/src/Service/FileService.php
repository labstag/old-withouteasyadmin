<?php

namespace Labstag\Service;

use Labstag\Entity\Attachment;
use Labstag\Repository\AttachmentRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    public function __construct(
        protected ContainerBagInterface $containerBag,
        protected AttachmentRepository $attachmentRepository
    )
    {
    }

    public function moveFile(
        UploadedFile $uploadedFile,
        string $path,
        string $filename,
        ?Attachment $attachment
    ): void
    {
        $uploadedFile->move(
            $path,
            $filename
        );
        $uploadedFile = $path.'/'.$filename;

        $this->setAttachment($uploadedFile, $attachment);
    }

    public function setAttachment(
        string $file,
        ?Attachment $attachment = null
    ): Attachment
    {
        if (is_null($attachment)) {
            $attachment = new Attachment();
        }

        /** @var Attachment $attachment */
        $attachment->setMimeType((string) mime_content_type($file));
        $attachment->setSize((int) filesize($file));
        $attachment->setName(
            str_replace(
                $this->containerBag->get('kernel.project_dir').'/public/',
                '',
                (string) $file
            )
        );
        $this->attachmentRepository->add($attachment);

        return $attachment;
    }
}
