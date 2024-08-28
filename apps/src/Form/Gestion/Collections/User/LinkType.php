<?php

namespace Labstag\Form\Gestion\Collections\User;

use Labstag\Entity\LinkUser;
use Labstag\Form\Gestion\LinkType as AbstractLinkType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractLinkType
{
    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        parent::configureOptions($optionsResolver);
        // Configure your form options here
        $optionsResolver->setDefaults(
            [
                'data_class' => LinkUser::class,
            ]
        );
    }
}
