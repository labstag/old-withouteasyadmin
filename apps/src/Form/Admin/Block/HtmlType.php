<?php

namespace Labstag\Form\Admin\Block;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Block\Html;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title');
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'attr'     => ['data-ckeditor' => 0],
                'label'    => 'Texte',
                'required' => false,
                'config'   => ['height' => '500px'],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Html::class,
            ]
        );
    }
}
