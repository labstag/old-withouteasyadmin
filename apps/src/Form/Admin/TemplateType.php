<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Template;
use Labstag\FormType\CoreTextareaType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractTypeLib
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
                'label' => $this->translator->trans('template.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('template.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'code',
            TextType::class,
            [
                'label' => $this->translator->trans('template.code.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.code.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('template.code.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'html',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('template.html.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.html.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'text',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('template.text.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.text.help', [], 'admin.form'),
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Template::class,
            ]
        );
    }
}
