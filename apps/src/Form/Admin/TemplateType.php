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
                'label' => $this->translator->trans('template.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('template.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $formBuilder->add(
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
        $formBuilder->add(
            'html',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('template.html.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.html.help', [], 'admin.form'),
            ]
        );
        $formBuilder->add(
            'text',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('template.text.label', [], 'admin.form'),
                'help'  => $this->translator->trans('template.text.help', [], 'admin.form'),
            ]
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Template::class,
            ]
        );
    }
}
