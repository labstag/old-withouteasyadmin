<?php

namespace Labstag\Form\Gestion\User;

use Labstag\Entity\Groupe;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('groupe.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('groupe.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.name.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'code',
            TextType::class,
            [
                'label' => $this->translator->trans('groupe.code.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('groupe.code.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.code.placeholder', [], 'gestion.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Groupe::class,
            ]
        );
    }
}
