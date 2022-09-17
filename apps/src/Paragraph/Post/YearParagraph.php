<?php

namespace Labstag\Paragraph\Post;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Year;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\YearType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class YearParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Year::class;
    }

    public function getForm(): string
    {
        return YearType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postyear.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postyear';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Year $postyear): Response
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $year       = $routeParam['year'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierArchive($year),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/year'),
            [
                'pagination' => $pagination,
                'paragraph'  => $postyear,
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
