<?php

namespace Labstag\Paragraph\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Bookmark\Category;
use Labstag\Form\Admin\Paragraph\Bookmark\CategoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;

class CategoryParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Category::class;
    }

    public function getForm()
    {
        return CategoryType::class;
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

    public function show(Category $bookmarkcategory)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var BookmarkRepository $repository */
        $repository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierCategory($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmark/category'),
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
