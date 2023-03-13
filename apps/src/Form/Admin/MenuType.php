<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Menu;
use Labstag\Form\Admin\Collections\MenuType as DataType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        unset($options);
        $entity = $formBuilder->getData();
        if (!$entity instanceof Menu) {
            return;
        }

        if (empty($entity->getClef())) {
            $this->setChildren($formBuilder);

            return;
        }

        $this->setNew($formBuilder);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Menu::class,
            ]
        );
    }

    private function setChildren(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('menu.link.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('menu.link.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('menu.link.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'icon',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.link.icon.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.link.icon.help', [], 'admin.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.link.icon.placeholder', [], 'admin.form'),
                ],
                'required' => false,
            ]
        );
        $formBuilder->add(
            'data',
            CollectionType::class,
            [
                'allow_add'    => false,
                'allow_delete' => false,
                'entry_type'   => DataType::class,
            ]
        );
    }

    private function setNew(FormBuilderInterface $formBuilder): void
    {
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
}
