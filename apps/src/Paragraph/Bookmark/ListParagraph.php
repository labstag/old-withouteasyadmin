<?php

namespace Labstag\Paragraph\Bookmark;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Bookmark\Liste;
use Labstag\Form\Admin\Paragraph\Bookmark\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;

class ListParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Liste::class;
    }

    public function getForm(): string
    {
        return ListType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('bookmarklist.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'bookmarklist';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Liste $liste): Response
    {
        /** @var BookmarkRepository $repository */
        $repository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('bookmark/list'),
            [
                'pagination' => $pagination,
                'paragraph'  => $liste,
            ]
        );
    }

    /**
     * @return array<class-string<Page>>
     */
    public function useIn(): array
    {
        return [
            Page::class,
        ];
    }
}
