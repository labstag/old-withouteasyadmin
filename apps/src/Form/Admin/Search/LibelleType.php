<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\LibelleSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->addName(
            $builder,
            $this->translator->trans('libelle.name.label', [], 'admin.search.form'),
            $this->translator->trans('libelle.name.help', [], 'admin.search.form'),
            $this->translator->trans('libelle.name.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => LibelleSearch::class,
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
