<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\GeoCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'help'  => 'admin.form.geocode.countryCode.help',
            ]
        );
        $tab = [
            'postalCode',
            'placeName',
            'stateName',
            'stateCode',
            'provinceName',
            'provinceCode',
            'communityName',
            'communityCode',
            'latitude',
            'longitude',
            'accuracy',
        ];

        $this->setInputText($builder, $tab);
    }

    private function setInputText($builder, $tab)
    {
        foreach (array_keys($tab) as $id) {
            $builder->add(
                $id,
                TextType::class,
                [
                    'label' => 'admin.form.param.tarteaucitron.'.$id.'.label',
                    'help'  => 'admin.form.param.tarteaucitron.'.$id.'.help',
                ]
            );
        }
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
