<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\GeocodeSearch;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeocodeType extends AbstractTypeLib
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
            'countrycode',
            CountryType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('geocode.countrycode.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.countrycode.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.countrycode.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $this->setTextType($builder);
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
                'data_class'      => GeocodeSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }

    protected function setTextType($builder)
    {
        $texttype = [
            'postalcode'    => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.postalcode.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.postalcode.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.postalcode.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'placename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.placename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.placename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.placename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'statename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.statename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.statename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.statename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'provincename'  => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.provincename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.provincename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.provincename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'communityname' => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.communityname.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.communityname.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.communityname.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $builder->add($key, TextType::class, $args);
        }
    }
}
