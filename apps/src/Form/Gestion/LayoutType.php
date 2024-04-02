<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\Block\CustomRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LayoutType extends AbstractTypeLib
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
                'label' => $this->translator->trans('layout.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('layout.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('layout.name.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $formBuilder->add(
            'custom',
            EntityType::class,
            [
                'class'         => Custom::class,
                'query_builder' => static fn (CustomRepository $customRepository) => $customRepository->formType(),
            ]
        );
        $all     = $this->guardService->getPublicRoute();
        $choices = [];
        foreach (array_keys($all) as $key) {
            $choices[$key] = $key;
        }

        $formBuilder->add(
            'url',
            ChoiceType::class,
            [
                'required' => false,
                'multiple' => true,
                'choices'  => $choices,
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_layout_paragraph_add',
                'edit'   => 'gestion_layout_paragraph_show',
                'delete' => 'gestion_layout_paragraph_delete',
            ]
        );

        $formBuilder->add(
            child: 'groupes',
            options: [
                'label' => $this->translator->trans('layout.groupes.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('layout.groupes.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('layout.groupes.placeholder', [], 'gestion.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Layout::class,
            ]
        );
    }
}
