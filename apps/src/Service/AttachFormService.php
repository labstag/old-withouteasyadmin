<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Interfaces\EntityInterface;
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

    public function upload(EntityInterface $entity): void
    {
        $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
        foreach ($annotations as $property => $annotation) {
            /** @var UploadableField $annotation */
            $accessor = PropertyAccess::createPropertyAccessor();
            $file     = $accessor->getValue($entity, $property);
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
            $path     = $this->containerBag->get('file_directory').'/'.$annotation->getPath();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $this->fileService->moveFile($file, $path, $filename, $attachment, $old);
            $filename = $annotation->getFilename();
            if (!is_string($filename)) {
                continue;
            }

            $accessor->setValue($entity, $filename, $attachment);
        }
    }

    protected function setAttachment(
        PropertyAccessor $propertyAccessor,
        EntityInterface $entity,
        UploadableField $uploadableField
    ): Attachment
    {
        $filename = $uploadableField->getFilename();
        if (!is_string($filename)) {
            return new Attachment();
        }

        $attachmentField = $propertyAccessor->getValue($entity, $filename);
        if (is_null($attachmentField) || !$attachmentField instanceof Attachment) {
            return new Attachment();
        }

        $attachment = $this->attachmentRepository->findOneBy(['id' => $attachmentField->getId()]);
        if (!$attachment instanceof Attachment) {
            $attachment = new Attachment();
        }

        return $attachment;
    }
}
