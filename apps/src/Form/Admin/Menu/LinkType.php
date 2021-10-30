<?php

namespace Labstag\Form\Admin\Menu;

use Labstag\Entity\Menu;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('menu.link.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('menu.link.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('menu.link.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'icon',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.link.icon.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.link.icon.help', [], 'admin.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.link.icon.placeholder', [], 'admin.form'),
                ],
                'required' => false,
            ]
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
