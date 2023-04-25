<?php

namespace Labstag\FormType;

use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Lib\FormTypeLib;
use Labstag\Repository\EmailUserRepository;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailVerifChoiceType extends FormTypeLib
{
    public function buildView(
        FormView $formView,
        FormInterface $form,
        array $options
    ): void {
        /** @var FormInterface $parent */
        $parent = $form->getParent();
        if (is_null($parent)) {
            return;
        }

        $entity = $parent->getData();
        if ($entity instanceof User) {
            return;
        }

        /** @var EmailUserRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(EmailUser::class);
        /** @var User $entity */
        $data    = $repositoryLib->getEmailsUserVerif($entity, true);
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
