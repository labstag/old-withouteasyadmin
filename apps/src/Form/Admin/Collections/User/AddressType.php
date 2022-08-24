<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\AddressUser;
use Labstag\Form\Admin\AddressType as AbstractAddressType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractAddressType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => AddressUser::class,
            ]
        );
    }
}
