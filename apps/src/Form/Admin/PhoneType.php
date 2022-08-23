<?php

namespace Labstag\Form\Admin;

use Labstag\FormType\FlagCountryType;
use Labstag\FormType\PhoneVerifType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;

abstract class PhoneType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'numero',
            PhoneVerifType::class,
            [
                'entity' => $options['data'],
            ]
        );
        $builder->add(
            'country',
            FlagCountryType::class
        );
        $builder->add('type');
    }
}
