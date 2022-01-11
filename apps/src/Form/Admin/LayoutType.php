<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Layout;
use Labstag\FormType\CoreTextareaType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LayoutType extends AbstractTypeLib
{
    /**
     * @inheritdoc
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
            'content',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('layout.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('layout.content.help', [], 'admin.form'),
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
