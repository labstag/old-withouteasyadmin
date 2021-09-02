<?php

namespace Labstag\Form\Admin\Menu;

use Doctrine\DBAL\Types\TextType;
use Labstag\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrincipalType extends AbstractType
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
                'label' => 'admin.form.menu.principal.clef.label',
                'help'  => 'admin.form.menu.principal.clef.help',
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
