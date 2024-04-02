<?php

namespace Labstag\Form\Gestion\Paragraph;

use Labstag\Entity\Paragraph\TextImage;
use Labstag\FormType\UploadType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextImageType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'leftimage',
            CheckboxType::class,
            [
                'required' => false,
                'label'    => 'image à gauche',
            ]
        );
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => 'title',
            ]
        );
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'required' => false,
                'label'    => 'slug',
            ]
        );
        $formBuilder->add(
            'content',
            WysiwygType::class,
            [
                'label'     => 'Texte',
                'help_html' => true,
                'help'      => $this->getRender('gestion/paragraph/shortcode.html.twig'),
            ]
        );
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('post.file.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('post.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => TextImage::class,
            ]
        );
    }
}
