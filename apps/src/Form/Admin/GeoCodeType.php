<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\GeoCode;
use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add(
            'countryCode',
            FlagCountryType::class,
            [
                'label' => $this->translator->trans('geocode.countryCode.label', [], 'admin.form'),
                'help'  => $this->translator->trans('geocode.countryCode.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('geocode.countryCode.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $tab = [
            'postalCode'    => [
                'label'       => $this->translator->trans('param.geocode.postalCode.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.postalCode.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.postalCode.placeholder', [], 'admin.form'),
            ],
            'placeName'     => [
                'label'       => $this->translator->trans('param.geocode.placeName.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.placeName.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.placeName.placeholder', [], 'admin.form'),
            ],
            'stateName'     => [
                'label'       => $this->translator->trans('param.geocode.stateName.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.stateName.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.stateName.placeholder', [], 'admin.form'),
            ],
            'stateCode'     => [
                'label'       => $this->translator->trans('param.geocode.stateCode.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.stateCode.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.stateCode.placeholder', [], 'admin.form'),
            ],
            'provinceName'  => [
                'label'       => $this->translator->trans('param.geocode.provinceName.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.provinceName.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.provinceName.placeholder', [], 'admin.form'),
            ],
            'provinceCode'  => [
                'label'       => $this->translator->trans('param.geocode.provinceCode.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.provinceCode.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.provinceCode.placeholder', [], 'admin.form'),
            ],
            'communityName' => [
                'label'       => $this->translator->trans('param.geocode.communityName.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.communityName.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.communityName.placeholder', [], 'admin.form'),
            ],
            'communityCode' => [
                'label'       => $this->translator->trans('param.geocode.communityCode.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.communityCode.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.communityCode.placeholder', [], 'admin.form'),
            ],
            'latitude'      => [
                'label'       => $this->translator->trans('param.geocode.latitude.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.latitude.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.latitude.placeholder', [], 'admin.form'),
            ],
            'longitude'     => [
                'label'       => $this->translator->trans('param.geocode.longitude.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.longitude.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.longitude.placeholder', [], 'admin.form'),
            ],
            'accuracy'      => [
                'label'       => $this->translator->trans('param.geocode.accuracy.label', [], 'admin.form'),
                'help'        => $this->translator->trans('param.geocode.accuracy.help', [], 'admin.form'),
                'placeholder' => $this->translator->trans('param.geocode.accuracy.placeholder', [], 'admin.form'),
            ],
        ];

        $this->setInputText($builder, $tab);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => GeoCode::class,
            ]
        );
    }
}
