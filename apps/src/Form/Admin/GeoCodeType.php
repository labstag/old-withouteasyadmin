<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\GeoCode;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add(
            'countryCode',
            CountryType::class,
            [
                'label' => $this->translator->trans('geocode.countryCode.label', [], 'form'),
                'help'  => $this->translator->trans('geocode.countryCode.help', [], 'form'),
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
                    'label' => $this->translator->trans('param.tarteaucitron.'.$id.'.label', [], 'form'),
                    'help'  => $this->translator->trans('param.tarteaucitron.'.$id.'.help', [], 'form'),
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
