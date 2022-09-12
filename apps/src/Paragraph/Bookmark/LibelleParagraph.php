<?php

namespace Labstag\Paragraph\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Bookmark\Libelle;
use Labstag\Form\Admin\Paragraph\Bookmark\LibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;

class LibelleParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Libelle::class;
    }

    public function getForm()
    {
        return LibelleType::class;
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

    public function show(Libelle $bookmarklibelle)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var BookmarkRepository $repository */
        $repository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierLibelle($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmark/libelle'),
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
