<?php

namespace Labstag\Form\Gestion\Search\User;

use Labstag\FormType\FlagCountryType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\AddressUserSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'country',
            FlagCountryType::class,
            ['required' => false]
        );
        $formBuilder->add(
            'city',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('addressuser.city.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('addressuser.city.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('addressuser.city.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $this->addRefUser($formBuilder);
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => AddressUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
