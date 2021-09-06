<?php

namespace Labstag\Form\Admin;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class LienType extends AbstractTypeLib
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('lien.name.label', [], 'form'),
                'help'  => $this->translator->trans('lien.name.help', [], 'form'),
            ]
        );
        $builder->add(
            'adresse',
            UrlType::class,
            [
                'label' => $this->translator->trans('lien.adresse.label', [], 'form'),
                'help'  => $this->translator->trans('lien.adresse.help', [], 'form'),
            ]
        );
    }
}
