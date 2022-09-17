<?php

namespace Labstag\Form\Admin\Menu;

use Labstag\Entity\Menu;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrincipalType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $formBuilder->add(
            'clef',
            TextType::class,
            [
                'label' => $this->translator->trans('menu.principal.clef.label', [], 'admin.form'),
                'help'  => $this->translator->trans('menu.principal.clef.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('menu.principal.clef.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Menu::class,
            ]
        );
    }
}
