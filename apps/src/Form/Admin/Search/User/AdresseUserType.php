<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\User;
use Labstag\FormType\FlagCountryType;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\AdresseUserSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'country',
            FlagCountryType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('adresseuser.country.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('adresseuser.country.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'adresseuser.country.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'ville',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('adresseuser.ville.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('adresseuser.ville.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('adresseuser.ville.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('adresseuser.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('adresseuser.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'adresseuser.refuser.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => AdresseUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
