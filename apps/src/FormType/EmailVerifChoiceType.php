<?php

namespace Labstag\FormType;

use Labstag\Entity\EmailUser;
use Labstag\Repository\EmailUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailVerifChoiceType extends AbstractType
{
    public function __construct(
        protected EmailUserRepository $emailUserRepository,
        protected TranslatorInterface $translator
    ) {
    }

    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void {
        /** @var FormInterface $parent */
        $parent  = $form->getParent();
        $entity  = $parent->getData();
        $data    = $this->emailUserRepository->getEmailsUserVerif($entity, true);
        $choices = [];
        foreach ($data as $email) {
            /** @var EmailUser $email */
            $address           = $email->getAddress();
            $choices[$address] = new ChoiceView('', (string) $address, (string) $address);
        }

        ksort($choices);

        if (0 != count($choices)) {
            $formView->vars['required'] = false;
        }

        $formView->vars['value']   = $entity->getEmail();
        $formView->vars['choices'] = $choices;
        unset($options, $form);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'label' => $this->translator->trans('forms.email.label', [], 'admin.form'),
                'help'  => $this->translator->trans('forms.email.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('forms.email.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
