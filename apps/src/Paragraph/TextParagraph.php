<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Text;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\TextType;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\HttpFoundation\Response;

class TextParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Text::class;
    }

    public function getForm(): string
    {
        return TextType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('text.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'text';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Text $text): Response
    {
        return $this->render(
            $this->getParagraphFile('text'),
            ['paragraph' => $text]
        );
    }

    /**
     * @return class-string[]
     */
    public function useIn(): array
    {
        return [
            Chapter::class,
            Edito::class,
            History::class,
            Layout::class,
            Memo::class,
            Page::class,
            Post::class,
        ];
    }
}
