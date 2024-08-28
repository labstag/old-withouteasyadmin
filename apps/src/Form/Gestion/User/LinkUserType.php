<?php

namespace Labstag\Form\Gestion\User;

use Labstag\Entity\LinkUser;
use Labstag\Entity\User;
use Labstag\Form\Gestion\LinkType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkUserType extends LinkType
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        parent::buildForm($formBuilder, $options);

        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('linkuser.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('linkuser.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('linkuser.refuser.placeholder', [], 'gestion.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => LinkUser::class,
            ]
        );
    }
}
