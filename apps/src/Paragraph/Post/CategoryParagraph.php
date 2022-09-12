<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Category;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\CategoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

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
        return $this->translator->trans('postcategory.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postcategory';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Category $postcategory)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierCategory($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/category'),
            [
                'pagination' => $pagination,
                'paragraph'  => $postcategory,
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
