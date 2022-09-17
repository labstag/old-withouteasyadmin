<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\EmailType as AbstractEmailType;
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
