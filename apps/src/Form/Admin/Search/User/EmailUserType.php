<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\User\EmailUserSearch;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailUserType extends AbstractTypeLib
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
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('emailuser.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('emailuser.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'emailuser.refuser.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => EmailUserSearch::class,
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
