<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\GeoCode;
use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        unset($options);
        $formBuilder->add(
            'countryCode',
            FlagCountryType::class
        );
        $tab = [
            'postalCode'    => [
                'label'       => $this->translator->trans('param.geocode.postalCode.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.postalCode.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.postalCode.placeholder', [], 'gestion.form'),
            ],
            'placeName'     => [
                'label'       => $this->translator->trans('param.geocode.placeName.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.placeName.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.placeName.placeholder', [], 'gestion.form'),
            ],
            'stateName'     => [
                'label'       => $this->translator->trans('param.geocode.stateName.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.stateName.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.stateName.placeholder', [], 'gestion.form'),
            ],
            'stateCode'     => [
                'label'       => $this->translator->trans('param.geocode.stateCode.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.stateCode.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.stateCode.placeholder', [], 'gestion.form'),
            ],
            'provinceName'  => [
                'label'       => $this->translator->trans('param.geocode.provinceName.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.provinceName.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.provinceName.placeholder', [], 'gestion.form'),
            ],
            'provinceCode'  => [
                'label'       => $this->translator->trans('param.geocode.provinceCode.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.provinceCode.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.provinceCode.placeholder', [], 'gestion.form'),
            ],
            'communityName' => [
                'label'       => $this->translator->trans('param.geocode.communityName.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.communityName.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.communityName.placeholder', [], 'gestion.form'),
            ],
            'communityCode' => [
                'label'       => $this->translator->trans('param.geocode.communityCode.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.communityCode.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.communityCode.placeholder', [], 'gestion.form'),
            ],
            'latitude'      => [
                'label'       => $this->translator->trans('param.geocode.latitude.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.latitude.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.latitude.placeholder', [], 'gestion.form'),
            ],
            'longitude'     => [
                'label'       => $this->translator->trans('param.geocode.longitude.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.longitude.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.longitude.placeholder', [], 'gestion.form'),
            ],
            'accuracy'      => [
                'label'       => $this->translator->trans('param.geocode.accuracy.label', [], 'gestion.form'),
                'help'        => $this->translator->trans('param.geocode.accuracy.help', [], 'gestion.form'),
                'placeholder' => $this->translator->trans('param.geocode.accuracy.placeholder', [], 'gestion.form'),
            ],
        ];

        $this->setInputText($formBuilder, $tab);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => GeoCode::class,
            ]
        );
    }
}
