<?php

namespace Labstag\Form\Admin\Collections\Form;

use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherFieldsType extends AbstractTypeLib
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
            'checkbox',
            CheckboxType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'file',
            UploadType::class,
            ['help' => 'help']
        );
        $formBuilder->add(
            'radio',
            RadioType::class,
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
