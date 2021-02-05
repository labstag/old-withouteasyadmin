<?php

namespace Labstag\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class EmailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        unset($options);
        $builder->add('adresse');
    }
}
