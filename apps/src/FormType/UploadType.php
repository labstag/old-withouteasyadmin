<?php

namespace Labstag\FormType;

use Labstag\Reader\UploadAnnotationReader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
        $property = $form->getName();
        $field = null;
        if (isset($annotations[$property])) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $field    = $accessor->getValue($entity, $annotations[$property]->getFileName());
        }

        $formView->vars['field'] = $field;
        $formView->vars['url']  = $this->router->generate('api_attachment_delete', ['entity' => $field->getId()]);
        unset($form);
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return FileType::class;
    }
}
