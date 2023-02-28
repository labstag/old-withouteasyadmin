<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Attachment;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class AttachFormService
{
    public function __construct(
        protected FileService $fileService,
        protected EntityManagerInterface $entityManager,
        protected ContainerBagInterface $containerBag,
        protected AttachmentRepository $attachmentRepository,
        private readonly UploadAnnotationReader $uploadAnnotationReader
    )
    {
    }

    public function upload($entity): void
    {
        $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
        foreach ($annotations as $property => $annotation) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $file = $accessor->getValue($entity, $property);
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $attachment = $this->setAttachment(
                $accessor,
                $entity,
                $annotation
            );
            $old = clone $attachment;

            $filename = $file->getClientOriginalName();
            $path = $this->containerBag->get('file_directory').'/'.$annotation->getPath();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $this->moveFile($file, $path, $filename, $attachment, $old);
            $accessor->setValue($entity, $annotation->getFilename(), $attachment);
        }
    }

    protected function moveFile(
        $file,
        $path,
        $filename,
        $attachment,
        $old
    ): void
    {
        $file->move(
            $path,
            $filename
        );
        $file = $path.'/'.$filename;

        $this->fileService->setAttachment($file, $attachment, $old);
    }

    protected function setAttachment(
        PropertyAccessor $propertyAccessor,
        mixed $entity,
        $annotation
    ): Attachment
    {
        $attachmentField = $propertyAccessor->getValue($entity, $annotation->getFilename());
        if (is_null($attachmentField)) {
            return new Attachment();
        }

        $attachment = $this->attachmentRepository->findOneBy(['id' => $attachmentField->getId()]);
        if (!$attachment instanceof Attachment) {
            $attachment = new Attachment();
        }

        return $attachment;
    }
}
