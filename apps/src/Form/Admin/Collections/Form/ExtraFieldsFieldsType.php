<?php

namespace Labstag\Form\Admin\Collections\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\FormType\CoreTextareaType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraFieldsFieldsType extends AbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'html',
            CKEditorType::class,
            ['help' => 'help']
        );
        $builder->add(
            'textarea',
            CoreTextareaType::class,
            ['help' => 'help']
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            []
        );
    }
}
