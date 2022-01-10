<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\CategorySearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add(
            'name',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('category.name.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('category.name.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('category.name.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => CategorySearch::class,
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
