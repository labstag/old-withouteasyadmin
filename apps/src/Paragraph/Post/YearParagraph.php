<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Year;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\YearType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class YearParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Year::class;
    }

    public function getForm()
    {
        return YearType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postyear.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postyear';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Year $postyear)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $year = $routeParam['year'] ?? null;
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

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }
}
