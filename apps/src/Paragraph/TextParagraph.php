<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Text;
use Labstag\Entity\Post;
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

    public function isShowForm()
    {
        return true;
    }

    public function show(Text $text)
    {
        return $this->render(
            $this->getParagraphFile('text'),
            ['paragraph' => $text]
        );
    }

    public function useIn()
    {
        return [
            Chapter::class,
            Edito::class,
            History::class,
            Memo::class,
            Page::class,
            Post::class,
        ];
    }
}
