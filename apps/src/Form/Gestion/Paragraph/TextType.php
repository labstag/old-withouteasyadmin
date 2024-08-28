<?php

namespace Labstag\Form\Gestion\Paragraph;

use Labstag\Entity\Paragraph\Text;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\ParagraphAbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextType extends ParagraphAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->add(
            'content',
            WysiwygType::class,
            [
                'label'     => 'Texte',
                'help_html' => true,
                'help'      => $this->getRender('gestion/paragraph/shortcode.html.twig'),
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Text::class,
            ]
        );
    }
}
