<?php

namespace Labstag\Form\Admin\User;

use Doctrine\DBAL\Types\TextType;
use Labstag\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
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
                'label' => 'admin.form.groupe.name.label',
                'help'  => 'admin.form.groupe.name.help',
            ]
        );
        $builder->add(
            'code',
            TextType::class,
            [
                'label' => 'admin.form.groupe.code.label',
                'help'  => 'admin.form.groupe.code.help',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Groupe::class,
            ]
        );
    }
}
