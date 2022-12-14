<?php

namespace Labstag\Form\Admin\User;

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
                'label' => $this->translator->trans('groupe.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('groupe.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'code',
            TextType::class,
            [
                'label' => $this->translator->trans('groupe.code.label', [], 'admin.form'),
                'help'  => $this->translator->trans('groupe.code.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.code.placeholder', [], 'admin.form'),
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
