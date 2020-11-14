<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\LienUser;
use Labstag\Form\Admin\LienType as AbstractLienType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienType extends AbstractLienType
{

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => LienUser::class,
            ]
        );
    }
}
