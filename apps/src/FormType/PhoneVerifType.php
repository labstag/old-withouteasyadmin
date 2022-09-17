<?php

namespace Labstag\FormType;

use Labstag\Entity\Phone;
use Labstag\Service\PhoneService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhoneVerifType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected PhoneService $phoneService
    )
    {
    }

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void
    {
        $phoneUser = $form->getParent()->getData();
        $verif     = false;
        if ($phoneUser instanceof Phone) {
            $phoneUser = $form->getParent()->getData();
            $country   = $phoneUser->getCountry();
            $number    = $phoneUser->getNumero();
            $verif     = $this->phoneService->verif($number, $country);
            $verif     = array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
        }

        $formView->vars['attr']['class'] = $verif ? 'is-valid' : 'is-invalid';

        unset($options, $form);
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults(
            [
                'label' => $this->translator->trans('forms.numero.label', [], 'admin.form'),
                'help'  => $this->translator->trans('forms.numero.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('forms.numero.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return TelType::class;
    }
}
