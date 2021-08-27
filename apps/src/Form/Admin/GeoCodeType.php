<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\GeoCode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options);
        $builder->add('countryCode');
        $builder->add('postalCode');
        $builder->add('placeName');
        $builder->add('stateName');
        $builder->add('stateCode');
        $builder->add('provinceName');
        $builder->add('provinceCode');
        $builder->add('communityName');
        $builder->add('communityCode');
        $builder->add('latitude');
        $builder->add('longitude');
        $builder->add('accuracy');
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
