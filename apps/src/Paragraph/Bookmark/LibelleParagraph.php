<?php

namespace Labstag\Paragraph\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Bookmark\Libelle;
use Labstag\Form\Admin\Paragraph\Bookmark\LibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\HttpFoundation\Response;

class LibelleParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Libelle::class;
    }

    public function getForm(): string
    {
        return LibelleType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('bookmarklibelle.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'bookmarklibelle';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Libelle $libelle): Response
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug = $routeParam['slug'] ?? null;
        /** @var BookmarkRepository $entityRepository */
        $entityRepository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublierLibelle($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmark/libelle'),
            [
                'pagination' => $pagination,
                'paragraph'  => $libelle,
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
