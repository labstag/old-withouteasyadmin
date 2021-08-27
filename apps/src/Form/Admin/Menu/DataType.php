<?php

namespace Labstag\Form\Admin\Menu;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DataType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'route',
            TextType::class,
            ['required' => false]
        );
        $builder->add(
            'param',
            TextType::class,
            ['required' => false]
        );
        $builder->add(
            'url',
            TextType::class,
            ['required' => false]
        );
        $builder->add(
            'target',
            ChoiceType::class,
            [
                'required' => false,
                'choices'  => [
                    ''        => '',
                    '_self'   => '_self',
                    '_blank'  => '_blank',
                    '_parent' => '_parent',
                    '_top'    => '_top',
                ],
            ]
        );
    }
}
