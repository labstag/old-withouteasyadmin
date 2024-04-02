<?php

namespace Labstag\Form\Gestion\Collections\Form;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateAndTimeFieldsType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'date',
            DateType::class,
            [
                'help'   => 'help',
                'widget' => 'single_text',
            ]
        );
        $formBuilder->add(
            'dateintervale',
            DateIntervalType::class,
            [
                'help'        => 'help',
                'placeholder' => [
                    'years'  => 'Years',
                    'months' => 'Months',
                    'days'   => 'Days',
                ],
            ]
        );
        $formBuilder->add(
            'datetimedate',
            DateTimeType::class,
            [
                'help'         => 'help',
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $formBuilder->add(
            'time',
            TimeType::class,
            [
                'help'         => 'help',
                'with_seconds' => true,
                'widget'       => 'single_text',
            ]
        );
        $formBuilder->add(
            'birthday',
            BirthdayType::class,
            [
                'help'   => 'help',
                'widget' => 'single_text',
            ]
        );
        $formBuilder->add(
            'week',
            WeekType::class,
            [
                'widget' => 'single_text',
                'help'   => 'help',
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            []
        );
    }
}
