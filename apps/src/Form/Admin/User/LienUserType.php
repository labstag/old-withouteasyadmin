<?php

namespace Labstag\Form\Admin\User;

use Labstag\Entity\LienUser;
use Labstag\Entity\User;
use Labstag\Form\Admin\LienType;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienUserType extends LienType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        parent::buildForm($builder, $options);

        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('lienuser.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('lienuser.refuser.help', [], 'admin.form'),
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
                'data_class' => LienUser::class,
            ]
        );
    }
}
