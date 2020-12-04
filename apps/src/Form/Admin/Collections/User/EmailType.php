<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\EmailType as AbstractEmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailType extends AbstractEmailType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => EmailUser::class,
            ]
        );
    }
}
