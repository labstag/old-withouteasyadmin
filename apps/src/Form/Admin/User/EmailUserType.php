<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\EmailType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailUserType extends EmailType
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        parent::buildForm($builder, $options);
        $builder->add(
            'principal',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('emailuser.principal.label', [], 'admin.form'),
                'help'  => $this->translator->trans('emailuser.principal.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('emailuser.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('emailuser.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => EmailUser::class,
            ]
        );
    }
}
