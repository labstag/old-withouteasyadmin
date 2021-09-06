<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Template;
use Labstag\FormType\CoreTextareaType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractTypeLib
{
    /**
     * {@inheritdoc}
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
                'label' => $this->translator->trans('template.name.label', [], 'form'),
                'help'  => $this->translator->trans('template.name.help', [], 'form'),
            ]
        );
        $builder->add(
            'code',
            TextType::class,
            [
                'label' => $this->translator->trans('template.code.label', [], 'form'),
                'help'  => $this->translator->trans('template.code.help', [], 'form'),
            ]
        );
        $builder->add(
            'html',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('template.html.label', [], 'form'),
                'help'  => $this->translator->trans('template.html.help', [], 'form'),
            ]
        );
        $builder->add(
            'text',
            CoreTextareaType::class,
            [
                'label' => $this->translator->trans('template.text.label', [], 'form'),
                'help'  => $this->translator->trans('template.text.help', [], 'form'),
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
