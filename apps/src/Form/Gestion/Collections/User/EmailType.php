<?php

namespace Labstag\Form\Gestion\Collections\User;

use Labstag\Entity\EmailUser;
use Labstag\Form\Gestion\EmailType as AbstractEmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractEmailType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);
        // Configure your form options here
        $optionsResolver->setDefaults(
            [
                'data_class' => EmailUser::class,
            ]
        );
    }
}
