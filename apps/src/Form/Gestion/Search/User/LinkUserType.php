<?php

namespace Labstag\Form\Gestion\Search\User;

use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\LinkUserSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $this->addRefUser($formBuilder);
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => LinkUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
