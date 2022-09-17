<?php

namespace Labstag\Paragraph\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Bookmark\Liste;
use Labstag\Form\Admin\Paragraph\Bookmark\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;

class ListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Liste::class;
    }

    public function getForm()
    {
        return ListType::class;
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

    public function show(Liste $liste)
    {
        /** @var BookmarkRepository $repository */
        $repository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmark/list'),
            [
                'pagination' => $pagination,
                'paragraph'  => $liste,
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
