<?php

namespace Labstag\Form\Admin\Menu;

use Doctrine\DBAL\Types\TextType;
use Labstag\Entity\Menu;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrincipalType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Menu::class,
            ]
        );
    }
}
