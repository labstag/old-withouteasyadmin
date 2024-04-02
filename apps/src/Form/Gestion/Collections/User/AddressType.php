<?php

namespace Labstag\Form\Gestion\Collections\User;

use Labstag\Entity\AddressUser;
use Labstag\Form\Gestion\AddressType as AbstractAddressType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractAddressType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);
        // Configure your form options here
        $optionsResolver->setDefaults(
            [
                'data_class' => AddressUser::class,
            ]
        );
    }
}
