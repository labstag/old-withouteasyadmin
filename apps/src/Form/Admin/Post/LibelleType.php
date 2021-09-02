<?php

namespace Labstag\Form\Admin\Post;

use Labstag\Entity\Libelle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'nom',
            TextType::class,
            [
                'label' => 'admin.form.libelle.nom.label',
                'help'  => 'admin.form.libelle.nom.help',
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => 'admin.form.libelle.nom.label',
                'help'     => 'admin.form.libelle.nom.help',
                'required' => false,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Libelle::class,
            ]
        );
    }
}
