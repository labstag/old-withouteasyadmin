<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Bookmark as EntityBookmark;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\BookmarkList;
use Labstag\Form\Admin\Paragraph\BookmarkListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\BookmarkListRepository;

class BookmarkListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return BookmarkList::class;
    }

    public function getForm()
    {
        return BookmarkListType::class;
    }

    public function getName()
    {
        return $this->translator->trans('bookmarklist.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'bookmarklist';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(BookmarkList $bookmarklist)
    {
        /** @var BookmarkListRepository $repository */
        $repository = $this->getRepository(EntityBookmark::class);
        $bookmarks  = $repository->getLimitOffsetResult($repository->findPublier(), 5, 0);

        return $this->render(
            $this->getParagraphFile('bookmarklist'),
            [
                'paragraph' => $bookmarklist,
                'bookmarks' => $bookmarks,
            ]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
