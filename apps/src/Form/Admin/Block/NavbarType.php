<?php

namespace Labstag\Form\Admin\Block;

use Labstag\Entity\Block\Navbar;
use Labstag\Entity\Menu;
use Labstag\Lib\BlockAbstractTypeLib;
use Labstag\Repository\MenuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'menu',
            EntityType::class,
            [
                'label'         => $this->translator->trans('block.navbar.menu.label', [], 'admin.form'),
                'help'          => $this->translator->trans('block.navbar.menu.help', [], 'admin.form'),
                'attr'          => [
                    'placeholder' => $this->translator->trans('block.navbar.menu.placeholder', [], 'admin.form'),
                ],
                'required'      => false,
                'class'         => Menu::class,
                'query_builder' => fn (MenuRepository $er) => $er->findAllCodeQuery(),
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Navbar::class,
            ]
        );
    }
}
