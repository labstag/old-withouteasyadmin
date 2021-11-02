<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\LinkUser;
use Labstag\Form\Admin\LinkType as AbstractLinkType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractLinkType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            [
                'data_class' => LinkUser::class,
            ]
        );
    }
}
