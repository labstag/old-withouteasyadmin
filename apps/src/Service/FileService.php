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

    public function setAttachment($file, $attachment = null, $old = null)
    {
        if (is_null($attachment) && is_null($old)) {
            $attachment = new Attachment();
            $old = clone $attachment;
        }

        $attachment->setMimeType(mime_content_type($file));
        $attachment->setSize(filesize($file));
        $attachment->setName(
            str_replace(
                $this->getParameter('kernel.project_dir').'/public/',
                '',
                (string) $file
            )
        );
        $this->attachmentRequestHandler->handle($old, $attachment);

        return $attachment;
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }
}
