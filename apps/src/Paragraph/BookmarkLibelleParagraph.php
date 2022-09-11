<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Bookmark as EntityBookmark;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\BookmarkLibelle;
use Labstag\Form\Admin\Paragraph\BookmarkLibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\BookmarkLibelleRepository;

class BookmarkLibelleParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return BookmarkLibelle::class;
    }

    public function getForm()
    {
        return BookmarkLibelleType::class;
    }

    public function getName()
    {
        return $this->translator->trans('bookmarklibelle.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'bookmarklibelle';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(BookmarkLibelle $bookmarklibelle)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $code       = $routeParam['code'] ?? null;
        /** @var BookmarkLibelleRepository $repository */
        $repository = $this->getRepository(EntityBookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierLibelle($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmarklibelle'),
            [
                'pagination' => $pagination,
                'paragraph'  => $bookmarklibelle,
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
