<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\GeoCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add(
            'countryCode',
            CountryType::class,
            [
                'label' => 'admin.form.geocode.countryCode.label',
                'help' => 'admin.form.geocode.countryCode.help',
            ]
        );
        $builder->add(
            'postalCode',
            TextType::class,
            [
                'label' => 'admin.form.geocode.postalCode.label',
                'help' => 'admin.form.geocode.postalCode.help',
            ]
        );
        $builder->add(
            'placeName',
            TextType::class,
            [
                'label' => 'admin.form.geocode.placeName.label',
                'help' => 'admin.form.geocode.placeName.help',
            ]
        );
        $builder->add(
            'stateName',
            TextType::class,
            [
                'label' => 'admin.form.geocode.stateName.label',
                'help' => 'admin.form.geocode.stateName.help',
            ]
        );
        $builder->add(
            'stateCode',
            TextType::class,
            [
                'label' => 'admin.form.geocode.stateCode.label',
                'help' => 'admin.form.geocode.stateCode.help',
            ]
        );
        $builder->add(
            'provinceName',
            TextType::class,
            [
                'label' => 'admin.form.geocode.provinceName.label',
                'help' => 'admin.form.geocode.provinceName.help',
            ]
        );
        $builder->add(
            'provinceCode',
            TextType::class,
            [
                'label' => 'admin.form.geocode.provinceCode.label',
                'help' => 'admin.form.geocode.provinceCode.help',
            ]
        );
        $builder->add(
            'communityName',
            TextType::class,
            [
                'label' => 'admin.form.geocode.communityName.label',
                'help' => 'admin.form.geocode.communityName.help',
            ]
        );
        $builder->add(
            'communityCode',
            TextType::class,
            [
                'label' => 'admin.form.geocode.communityCode.label',
                'help' => 'admin.form.geocode.communityCode.help',
            ]
        );
        $builder->add(
            'latitude',
            TextType::class,
            [
                'label' => 'admin.form.geocode.latitude.label',
                'help' => 'admin.form.geocode.latitude.help',
            ]
        );
        $builder->add(
            'longitude',
            TextType::class,
            [
                'label' => 'admin.form.geocode.longitude.label',
                'help' => 'admin.form.geocode.longitude.help',
            ]
        );
        $builder->add(
            'accuracy',
            TextType::class,
            [
                'label' => 'admin.form.geocode.accuracy.label',
                'help' => 'admin.form.geocode.accuracy.help',
            ]
        );
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
