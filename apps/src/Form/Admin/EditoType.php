<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\FormType\WysiwygType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends AbstractType
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
        $builder->add('title');
        $builder->add('content', WysiwygType::class);
        $builder->add('enable');
        $builder->add(
            'refuser',
            EntityType::class,
            [
                'attr'    => ['is' => 'select-refuser'],
                'class'   => User::class,
                'choices' => [$options['data']->getRefUser()],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Edito::class,
            ]
        );
    }
}
