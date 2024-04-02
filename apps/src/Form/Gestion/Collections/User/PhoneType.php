<?php

namespace Labstag\Form\Gestion\Collections\User;

use Labstag\Entity\PhoneUser;
use Labstag\Form\Gestion\PhoneType as AbstractPhoneType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneType extends AbstractPhoneType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);
        // Configure your form options here
        $optionsResolver->setDefaults(
            [
                'data_class' => PhoneUser::class,
            ]
        );
    }
}
