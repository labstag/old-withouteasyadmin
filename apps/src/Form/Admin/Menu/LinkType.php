<?php

namespace Labstag\Form\Admin\Menu;

use Labstag\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'libelle',
            TextType::class,
            []
        );
        $builder->add(
            'icon',
            TextType::class,
            ['required' => false]
        );
        $builder->add(
            'data',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => DataType::class,
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Menu::class,
            ]
        );
    }
}
