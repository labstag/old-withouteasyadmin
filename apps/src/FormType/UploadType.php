<?php

namespace Labstag\FormType;

use Labstag\Entity\Attachment;
use Labstag\Reader\UploadAnnotationReader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

class UploadType extends AbstractType
{
    public function __construct(
        private readonly UploadAnnotationReader $uploadAnnotationReader,
        protected RouterInterface $router
    )
    {
    }

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        /** @var FormInterface $parent */
        $parent = $form->getParent();
        if (is_null($parent)) {
            return;
        }

        $entity = $parent->getData();
        $name   = $form->getName();
        $field  = null;
        if (!is_array($entity)) {
            $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
            if (isset($annotations[$name])) {
                $propertyAccessor = PropertyAccess::createPropertyAccessor();
                $filename         = $annotations[$name]->getFileName();
                if (is_string($filename)) {
                    $field = $propertyAccessor->getValue($entity, $filename);
                }
            }
        } elseif (isset($entity[$name]) && $entity[$name] instanceof Attachment) {
            $field = $entity[$name];
        }

        $formView->vars['field'] = $field;
        $formView->vars['url']   = null;
        if ($field instanceof Attachment) {
            $route = $this->router->generate(
                'api_attachment_delete',
                ['attachment' => $field->getId()]
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
}
