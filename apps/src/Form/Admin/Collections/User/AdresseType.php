<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\AdresseUser;
use Labstag\Form\Admin\AdresseType as AbstractAdresseType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractAdresseType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => AdresseUser::class,
            ]
        );
    }
}
