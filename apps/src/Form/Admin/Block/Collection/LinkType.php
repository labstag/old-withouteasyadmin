<?php

namespace Labstag\Form\Admin\Block\Collection;

use Labstag\Entity\Block\Link;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'url',
            TextType::class,
            [
                'required' => false,
                'label'    => 'Url',
            ]
        );
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => 'title',
            ]
        );
        $formBuilder->add('external');
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Link::class,
            ]
        );
    }
}
