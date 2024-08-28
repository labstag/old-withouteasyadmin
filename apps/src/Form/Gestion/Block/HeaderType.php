<?php

namespace Labstag\Form\Gestion\Block;

use Labstag\Entity\Block\Header;
use Labstag\Form\Gestion\Block\Collection\LinkType;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeaderType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'links',
            CollectionType::class,
            [
                'attr'         => ['data-limit' => 1],
                'label'        => 'Contact',
                'entry_type'   => LinkType::class,
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Header::class,
            ]
        );
    }
}
