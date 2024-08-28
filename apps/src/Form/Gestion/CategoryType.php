<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Category;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractTypeLib
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
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('category.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('category.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('category.name.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('category.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('category.slug.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('category.slug.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'parent',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('category.parent.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('category.parent.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => Category::class,
                'required' => false,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('category.parent.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Category::class,
            ]
        );
    }
}
