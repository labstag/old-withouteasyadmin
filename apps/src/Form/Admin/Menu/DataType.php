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
            [
                'label'    => 'admin.form.menu.data.route.name.label',
                'help'     => 'admin.form.menu.data.route.name.help',
                'required' => false,
            ]
        );
        $builder->add(
            'param',
            TextType::class,
            [
                'label'    => 'admin.form.menu.data.route.param.label',
                'help'     => 'admin.form.menu.data.route.param.help',
                'required' => false,
            ]
        );
        $builder->add(
            'url',
            TextType::class,
            [
                'label'    => 'admin.form.menu.data.route.url.label',
                'help'     => 'admin.form.menu.data.route.url.help',
                'required' => false,
            ]
        );
        $builder->add(
            'target',
            ChoiceType::class,
            [
                'label'    => 'admin.form.menu.data.route.target.label',
                'help'     => 'admin.form.menu.data.route.target.help',
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
