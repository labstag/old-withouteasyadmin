<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Template;
use Labstag\FormType\CoreTextareaType;
use Labstag\FormType\WysiwygType;
use Symfony\Component\Form\AbstractType;
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
    ): void {
        unset($options);
        $builder->add('name');
        $builder->add('code');
        $builder->add('html', WysiwygType::class);
        $builder->add('text', CoreTextareaType::class);
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
