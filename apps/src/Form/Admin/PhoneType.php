<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\PhoneUser;
use Labstag\FormType\FlagCountryType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Service\PhoneService;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class PhoneType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected PhoneService $phoneService,
        TemplatePageService $templatePageService
    )
    {
        parent::__construct($translator, $templatePageService);
    }

    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $optionsInput = [
            'label' => $this->translator->trans('phone.numero.label', [], 'admin.form'),
            'help'  => $this->translator->trans('phone.numero.help', [], 'admin.form'),
            'attr'  => [
                'placeholder' => $this->translator->trans('phone.numero.placeholder', [], 'admin.form'),
            ],
        ];
        if (array_key_exists('data', $options)) {
            // @var PhoneUser $phoneuser
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
            FlagCountryType::class,
            [
                'label' => $this->translator->trans('phone.country.label', [], 'admin.form'),
                'help'  => $this->translator->trans('phone.country.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('phone.country.placeholder', [], 'admin.form'),
                    'is'          => 'select-country',
                    'choices'     => 'true',
                ],
            ]
        );
        $builder->add('type');
    }
}
