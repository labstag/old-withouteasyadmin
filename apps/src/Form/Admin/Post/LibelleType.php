<?php

namespace Labstag\Form\Admin\Post;

use Labstag\Entity\Libelle;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LibelleType extends AbstractTypeLib
{
    /**
     * {@inheritdoc}
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
                'label' => $this->translator->trans('libelle.nom.label', [], 'form'),
                'help'  => $this->translator->trans('libelle.nom.help', [], 'form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('libelle.nom.label', [], 'form'),
                'help'     => $this->translator->trans('libelle.nom.help', [], 'form'),
                'required' => false,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Libelle::class,
            ]
        );
    }
}
