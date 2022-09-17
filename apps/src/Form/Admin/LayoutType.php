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
        protected GuardService $service
    )
    {
        parent::__construct($translator);
    }

    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
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
        $builder->add(
            'custom',
            EntityType::class,
            [
                'class'         => Custom::class,
                'query_builder' => static fn(CustomRepository $er) => $er->formType(),
            ]
        );
        $all     = $this->service->getPublicRoute();
        $choices = [];
        foreach (array_keys($all) as $key) {
            $choices[$key] = $key;
        }

        $builder->add(
            'url',
            ChoiceType::class,
            [
                'required' => false,
                'multiple' => true,
                'choices'  => $choices,
            ]
        );
        $this->addParagraph(
            $builder,
            [
                'add'    => 'admin_layout_paragraph_add',
                'edit'   => 'admin_layout_paragraph_show',
                'delete' => 'admin_layout_paragraph_delete',
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Layout::class,
            ]
        );
    }
}
