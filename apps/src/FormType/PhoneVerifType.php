<?php

namespace Labstag\FormType;

use Labstag\Service\PhoneService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhoneVerifType extends AbstractType
{
    public function __construct(
        protected TranslatorInterface $translator,
        protected PhoneService $phoneService,
        protected RouterInterface $router
    )
    {
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $attr  = $options['attr'];
        $verif = false;
        if (isset($options['entity'])) {
            $phoneUser = $options['entity'];
            $country   = $phoneUser->getCountry();
            $number    = $phoneUser->getNumero();
            $verif     = $this->phoneService->verif($number, $country);
            $verif     = array_key_exists('isvalid', $verif) ? $verif['isvalid'] : false;
        }

        $attr['class'] = $verif ? 'is-valid' : 'is-invalid';

        $view->vars['attr'] = $attr;
        unset($form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'entity' => null,
                'label'  => $this->translator->trans('forms.numero.label', [], 'admin.form'),
                'help'   => $this->translator->trans('forms.numero.help', [], 'admin.form'),
                'attr'   => [
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
