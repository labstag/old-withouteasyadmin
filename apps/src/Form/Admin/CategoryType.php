<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Category;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractTypeLib
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
                'label' => $this->translator->trans('category.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('category.name.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('category.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('category.slug.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'parent',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('category.parent.label', [], 'admin.form'),
                'help'     => $this->translator->trans('category.parent.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Category::class,
                'required' => false,
                'route'    => 'api_search_category',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Category::class,
            ]
        );
    }
}
