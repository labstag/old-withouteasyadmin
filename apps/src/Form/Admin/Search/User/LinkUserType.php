<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\LinkUserSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('linkuser.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('linkuser.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'linkuser.refuser.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => LinkUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
