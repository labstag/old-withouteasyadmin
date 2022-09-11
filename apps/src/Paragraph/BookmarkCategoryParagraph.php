<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Bookmark as EntityBookmark;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\BookmarkCategory;
use Labstag\Form\Admin\Paragraph\BookmarkCategoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\BookmarkCategoryRepository;

class BookmarkCategoryParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return BookmarkCategory::class;
    }

    public function getForm()
    {
        return BookmarkCategoryType::class;
    }

    public function getName()
    {
        return $this->translator->trans('bookmarkcategory.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'bookmarkcategory';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(BookmarkCategory $bookmarkcategory)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $code       = $routeParam['code'] ?? null;
        /** @var BookmarkCategoryRepository $repository */
        $repository = $this->getRepository(EntityBookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierCategory($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmarkcategory'),
            [
                'pagination' => $pagination,
                'paragraph'  => $bookmarkcategory,
            ]
        );
    }

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }
}
