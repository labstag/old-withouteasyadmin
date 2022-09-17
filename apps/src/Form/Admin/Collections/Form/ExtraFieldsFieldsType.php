<?php

namespace Labstag\Form\Admin\Collections\Form;

use Labstag\FormType\CoreTextareaType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraFieldsFieldsType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'html',
            WysiwygType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'textarea',
            CoreTextareaType::class,
            ['help' => 'help']
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            []
        );
    }
}
