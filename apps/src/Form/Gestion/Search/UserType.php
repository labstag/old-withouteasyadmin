<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\UserSearch;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'username',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.username.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('user.username.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.username.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $formBuilder->add(
            'email',
            EmailType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.email.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('user.email.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.email.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $formBuilder->add(
            'groupe',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.refgroup.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('user.refgroup.help', [], 'gestion.search.form'),
                'multiple' => false,
                'class'    => Groupe::class,
                'route'    => 'api_search_group',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'user.refgroup.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ]
        );
        $this->showState(
            $formBuilder,
            new User(),
            $this->translator->trans('user.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('user.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('user.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => UserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
