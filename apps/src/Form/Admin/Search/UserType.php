<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\UserSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'username',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.username.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('user.username.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.username.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'email',
            EmailType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.email.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('user.email.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.email.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $workflow   = $this->workflows->get(new User());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('user.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('user.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
                'attr'     => [
                    'placeholder' => $this->translator->trans('user.etape.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => UserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
