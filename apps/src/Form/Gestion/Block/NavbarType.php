<?php

namespace Labstag\Form\Gestion\Block;

use Labstag\Entity\Block\Navbar;
use Labstag\Entity\Menu;
use Labstag\Lib\BlockAbstractTypeLib;
use Labstag\Repository\MenuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'menu',
            EntityType::class,
            [
                'label'         => $this->translator->trans('block.navbar.menu.label', [], 'gestion.form'),
                'help'          => $this->translator->trans('block.navbar.menu.help', [], 'gestion.form'),
                'attr'          => [
                    'placeholder' => $this->translator->trans('block.navbar.menu.placeholder', [], 'gestion.form'),
                ],
                'required'      => false,
                'class'         => Menu::class,
                'query_builder' => static fn (MenuRepository $menuRepository) => $menuRepository->findAllCodeQuery(),
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Navbar::class,
            ]
        );
    }
}
