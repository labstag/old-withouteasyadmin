<?php

namespace Labstag\FormType;

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
        protected EmailUserRepository $repository,
        protected TranslatorInterface $translator
    )
    {
    }

    public function buildView(
        FormView $view,
        FormInterface $form,
        array $options
    ): void
    {
        $entity = $form->getParent()->getData();
        $data   = $this->repository->getEmailsUserVerif($entity, true);
        $emails = [];
        foreach ($data as $email) {
            // @var EmailUser $email
            $address          = $email->getAddress();
            $emails[$address] = new ChoiceView('', $address, $address);
        }

        ksort($emails);

        if (0 != count($emails)) {
            $view->vars['required'] = false;
        }

        $view->vars['value']   = $entity->getEmail();
        $view->vars['choices'] = $emails;
        unset($options, $form);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
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
