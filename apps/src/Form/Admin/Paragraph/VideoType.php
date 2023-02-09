<?php

namespace Labstag\Form\Admin\Paragraph;

use Labstag\Entity\Paragraph\Video;
use Labstag\FormType\UploadType;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label' => 'title'
            ]);
        $formBuilder->add(
            'url',
            TextType::class,
            [
                'required' => false,
                'label' => 'url'
            ]);
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'required' => false,
                'label' => 'slug'
            ]);
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('post.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('post.file.help', [], 'admin.form'),
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
                'data_class' => Video::class,
            ]
        );
    }
}
