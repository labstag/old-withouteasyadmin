<?php

namespace Labstag\Form\Admin;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\EmailType as TypeEmailType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class EmailType extends AbstractTypeLib
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
            'adresse',
            TypeEmailType::class,
            [
                'label' => $this->translator->trans('email.adresse.label', [], 'admin.form'),
                'help'  => $this->translator->trans('email.adresse.help', [], 'admin.form'),
            ]
        );
    }
}
