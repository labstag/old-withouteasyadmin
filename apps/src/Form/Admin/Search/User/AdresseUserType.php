<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\User\AdresseUserSearch;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseUserType extends AbstractTypeLib
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
            CountryType::class,
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
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        unset($options);
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
