<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Bookmark;
use Labstag\Form\Admin\Paragraph\BookmarkType;
use Labstag\Lib\ParagraphLib;

class BookmarkParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Bookmark::class;
    }

    public function getForm()
    {
        return BookmarkType::class;
    }

    public function getName()
    {
        return $this->translator->trans('bookmark.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'bookmark';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Bookmark $bookmark)
    {
        return $this->render(
            $this->getParagraphFile('bookmark'),
            ['paragraph' => $bookmark]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
