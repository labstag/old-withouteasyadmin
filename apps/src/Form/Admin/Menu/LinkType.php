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
    ): void {
        unset($options);
        $builder->add(
            'libelle',
            TextType::class,
            [
                'label' => $this->translator->trans('menu.link.libelle.label', [], 'admin.form'),
                'help'  => $this->translator->trans('menu.link.libelle.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'icon',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.link.icon.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.link.icon.help', [], 'admin.form'),
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
