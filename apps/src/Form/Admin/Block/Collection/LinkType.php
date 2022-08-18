<?php

namespace Labstag\Form\Admin\Block\Collection;

use Labstag\Entity\Block\Link;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'url',
            TextType::class,
            [
                'required' => false,
                'label'    => 'Url',
            ]
        );
        $builder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => 'title',
            ]
        );
        $builder->add('external');
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Link::class,
            ]
        );
    }
}
