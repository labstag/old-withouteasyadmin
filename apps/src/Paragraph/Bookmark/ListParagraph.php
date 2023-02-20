<?php

namespace Labstag\Paragraph\Bookmark;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Bookmark\Liste;
use Labstag\Form\Admin\Paragraph\Bookmark\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\HttpFoundation\Response;

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

    public function getCode($liste): string
    {
        unset($liste);
        return 'bookmark/list';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Liste $liste): Response
    {
        /** @var BookmarkRepository $entityRepository */
        $entityRepository = $this->getRepository(Bookmark::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($liste)),
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
