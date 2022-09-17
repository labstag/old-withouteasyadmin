<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\Block\CustomRepository;
use Labstag\Service\GuardService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class LayoutType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected GuardService $guardService
    )
    {
        parent::__construct($translator);
    }

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
                'label' => $this->translator->trans('layout.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('layout.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('layout.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
            'custom',
            EntityType::class,
            [
                'class'         => Custom::class,
                'query_builder' => static fn(CustomRepository $customRepository) => $customRepository->formType(),
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
                'add'    => 'admin_layout_paragraph_add',
                'edit'   => 'admin_layout_paragraph_show',
                'delete' => 'admin_layout_paragraph_delete',
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
