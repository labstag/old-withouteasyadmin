<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\PhoneUser;
use Labstag\Service\PhoneService;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class PhoneType extends AbstractTypeLib
{

    protected PhoneService $phoneService;

    public function __construct(PhoneService $phoneService)
    {
        $this->phoneService = $phoneService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $optionsInput = [
            'label' => $this->translator->trans('phone.numero.label', [], 'admin.form'),
            'help'  => $this->translator->trans('phone.numero.help', [], 'admin.form'),
        ];
        if (array_key_exists('data', $options)) {
            /* @var PhoneUser $phoneuser */
            $phoneUser = $options['data'];
            $country   = $phoneUser->getCountry();
            $number    = $phoneUser->getNumero();
            $verif     = $this->phoneService->verif($number, $country);
            $verif     = array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;

            $optionsInput['attr']['class'] = $verif ? 'is-valid' : 'is-invalid';
        }

        $builder->add(
            'numero',
            TelType::class,
            $optionsInput
        );
        $builder->add(
            'country',
            CountryType::class,
            [
                'label' => $this->translator->trans('phone.country.label', [], 'admin.form'),
                'help'  => $this->translator->trans('phone.country.help', [], 'admin.form'),
                'attr'  => [
                    'is'      => 'select-country',
                    'choices' => 'true',
                ],
            ]
        );
        $builder->add('type');
    }
}
