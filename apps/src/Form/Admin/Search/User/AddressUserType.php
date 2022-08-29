<?php

namespace Labstag\Form\Admin\Search\User;

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
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'country',
            FlagCountryType::class,
            ['required' => false]
        );
        $builder->add(
            'city',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('addressuser.city.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('addressuser.city.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('addressuser.city.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $this->addRefUser($builder);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => AddressUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
