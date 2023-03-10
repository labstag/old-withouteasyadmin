<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Category;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\CategoryType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post/category';
    }

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
        /** @var Request $request */
        $request    = $this->requestStack->getCurrentRequest();
        $all        = $request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(Post::class);
        $pagination                 = $this->paginator->paginate(
            $serviceEntityRepositoryLib->findPublierCategory($slug),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($category)),
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
