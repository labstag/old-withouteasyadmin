<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Libelle;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends AbstractTypeLib
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
                'label' => $this->translator->trans('libelle.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('libelle.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('libelle.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('libelle.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('libelle.slug.help', [], 'admin.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('libelle.slug.placeholder', [], 'admin.form'),
                ],
                'required' => false,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Libelle::class,
            ]
        );
    }
}
