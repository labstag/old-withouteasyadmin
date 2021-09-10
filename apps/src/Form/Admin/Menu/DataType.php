<?php

namespace Labstag\Form\Admin\Menu;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DataType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add(
            'route',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.name.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.name.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'param',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.param.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.param.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'url',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.url.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.url.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'target',
            ChoiceType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.target.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.target.help', [], 'admin.form'),
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
