<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\LibelleSearch;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends AbstractTypeLib
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
            'nom',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('libelle.nom.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('libelle.nom.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('libelle.nom.placeholder', [], 'admin.search.form'),
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
                'data_class'      => LibelleSearch::class,
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
