<?php

namespace Labstag\FormType;

use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\FormTypeLib;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void {
        /** @var FormInterface $parent */
        $parent = $form->getParent();
        if (is_null($parent)) {
            return;
        }

        $entity = $parent->getData();
        $name   = $form->getName();
        if ($entity instanceof EntityInterface) {
            $attachment = $this->setFieldEntity($entity, $name);
        }

        if (is_array($entity)) {
            $attachment = $this->setFieldArray($entity, $name);
        }

        if (isset($attachment) && $attachment instanceof Attachment) {
            $formView->vars['field'] = $attachment;
        }

        $formView->vars['url'] = null;
        if ($attachment instanceof Attachment) {
            $route = $this->router->generate(
                'api_attachment_delete',
                ['attachment' => $attachment->getId()]
            );
            $formView->vars['url'] = $route;
        }

        unset($form, $options);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return FileType::class;
    }

    private function setFieldArray(
        array $entity,
        string $name
    ): ?Attachment {
        return (isset($entity[$name]) && $entity[$name] instanceof Attachment) ? $entity[$name] : null;
    }

    private function setFieldEntity(
        ?EntityInterface $entity,
        string $name
    ): ?Attachment {
        $field = null;
        if (!$entity instanceof EntityInterface) {
            return null;
        }

        $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
        if (isset($annotations[$name]) && $annotations[$name] instanceof UploadableField) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $filename         = $annotations[$name]->getFilename();
            if (is_string($filename)) {
                $field = $propertyAccessor->getValue($entity, $filename);
            }
        }

        if (!$field instanceof Attachment) {
            return null;
        }

        return $field;
    }
}
