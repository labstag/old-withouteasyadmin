<?php

namespace Labstag\Form\Admin\Collections\User;

use Labstag\Entity\LinkUser;
use Labstag\Form\Admin\LinkType as AbstractLinkType;
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
