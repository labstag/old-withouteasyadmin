<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Paragraph\Text;
use Labstag\Form\Admin\Paragraph\TextType;
use Labstag\Lib\ParagraphLib;

class TextParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Text::class;
    }

    public function getForm()
    {
        return TextType::class;
    }

    public function getName()
    {
        return $this->translator->trans('text.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'text';
    }

    public function show(Text $text)
    {
        return $this->render(
            $this->getParagraphFile('text'),
            ['paragraph' => $text]
        );
    }
}
