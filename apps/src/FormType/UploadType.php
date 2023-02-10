<?php

namespace Labstag\FormType;

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
        $entity = $form->getParent()->getData();
        $annotations = $this->uploadAnnotationReader->getUploadableFields($entity);
        $name = $form->getName();
        $field = null;
        if (isset($annotations[$name])) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $field = $propertyAccessor->getValue($entity, $annotations[$name]->getFileName());
        }

        $formView->vars['field'] = $field;
        $formView->vars['url'] = $this->router->generate('api_attachment_delete', ['entity' => $field->getId()]);
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
