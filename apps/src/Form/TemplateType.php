<?php

namespace Labstag\Form;

use Labstag\Entity\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add('name');
        $builder->add('code');
        $builder->add('html');
        $builder->add('text');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Template::class,
            ]
        );
    }
}
