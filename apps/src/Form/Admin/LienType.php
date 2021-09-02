<?php

namespace Labstag\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class LienType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
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
                'label' => 'admin.form.lien.name.label',
                'help'  => 'admin.form.lien.name.help',
            ]
        );
        $builder->add(
            'adresse',
            UrlType::class,
            [
                'label' => 'admin.form.lien.adresse.label',
                'help'  => 'admin.form.lien.adresse.help',
            ]
        );
    }
}
