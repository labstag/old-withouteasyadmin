<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\Collection\LinkType;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooterType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'links',
            CollectionType::class,
            [
                'label'        => 'Liens',
                'entry_type'   => LinkType::class,
                'by_reference' => false,
                'allow_add'    => true,
                'allow_delete' => true,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Footer::class,
            ]
        );
    }
}
