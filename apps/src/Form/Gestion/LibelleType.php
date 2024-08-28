<?php

namespace Labstag\Form\Gestion;

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
                'label' => $this->translator->trans('libelle.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('libelle.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('libelle.name.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('libelle.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('libelle.slug.help', [], 'gestion.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('libelle.slug.placeholder', [], 'gestion.form'),
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
