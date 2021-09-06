<?php

namespace Labstag\Form\Admin\Collections\Form;

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
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'date',
            DateType::class,
            [
                'help'   => $this->translator->trans('help', [], 'form'),
                'widget' => 'single_text',
            ]
        );
        $builder->add(
            'dateintervale',
            DateIntervalType::class,
            [
                'help'        => $this->translator->trans('help', [], 'form'),
                'placeholder' => [
                    'years'  => 'Years',
                    'months' => 'Months',
                    'days'   => 'Days',
                ],
            ]
        );
        $builder->add(
            'datetimedate',
            DateTimeType::class,
            [
                'help'         => $this->translator->trans('help', [], 'form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'time',
            TimeType::class,
            [
                'help'         => $this->translator->trans('help', [], 'form'),
                'with_seconds' => true,
                'widget'       => 'single_text',
            ]
        );
        $builder->add(
            'birthday',
            BirthdayType::class,
            [
                'help'   => $this->translator->trans('help', [], 'form'),
                'widget' => 'single_text',
            ]
        );
        $builder->add(
            'week',
            WeekType::class,
            [
                'widget' => 'single_text',
                'help'   => $this->translator->trans('help', [], 'form'),
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            []
        );
    }
}
