<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Template;
use Labstag\FormType\CoreTextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
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
                'label' => 'admin.form.template.name.label',
                'help'  => 'admin.form.template.name.help',
            ]
        );
        $builder->add(
            'code',
            TextType::class,
            [
                'label' => 'admin.form.template.code.label',
                'help'  => 'admin.form.template.code.help',
            ]
        );
        $builder->add(
            'html',
            CKEditorType::class,
            [
                'label' => 'admin.form.template.html.label',
                'help'  => 'admin.form.template.html.help',
            ]
        );
        $builder->add(
            'text',
            CoreTextareaType::class,
            [
                'label' => 'admin.form.template.text.label',
                'help'  => 'admin.form.template.text.help',
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
