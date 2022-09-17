<?php

namespace Labstag\Paragraph\Post;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Category;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\CategoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class CategoryParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Category::class;
    }

    public function getForm(): string
    {
        return CategoryType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postcategory.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postcategory';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Category $category): Response
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $entityRepository */
        $entityRepository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublierCategory($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/category'),
            [
                'pagination' => $pagination,
                'paragraph'  => $category,
            ]
        );
    }

    /**
     * @return array<class-string<Layout>>
     */
    public function useIn(): array
    {
        return [
            Layout::class,
        ];
    }
}
