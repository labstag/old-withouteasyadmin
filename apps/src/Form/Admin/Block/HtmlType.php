<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Html;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add('title');
        $formBuilder->add(
            'content',
            WysiwygType::class,
            [
                'label'    => 'Texte',
                'required' => false,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Html::class,
            ]
        );
    }
}
