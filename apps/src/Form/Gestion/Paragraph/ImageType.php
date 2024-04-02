<?php

namespace Labstag\Form\Gestion\Paragraph;

use Labstag\Entity\Paragraph\Image;
use Labstag\FormType\UploadType;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
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
                'data_class' => Image::class,
            ]
        );
    }
}
