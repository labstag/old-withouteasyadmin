<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Bookmark as EntityBookmark;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Bookmark;
use Labstag\Form\Admin\Paragraph\BookmarkType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\BookmarkRepository;
use Symfony\Component\HttpFoundation\Response;

class BookmarkParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'bookmark';
    }

    public function getEntity(): string
    {
        return Bookmark::class;
    }

    public function getForm(): string
    {
        return BookmarkType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('bookmark.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'bookmark';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Bookmark $bookmark): Response
    {
        /** @var BookmarkRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(EntityBookmark::class);
        $bookmarks = $serviceEntityRepositoryLib->getLimitOffsetResult(
            $serviceEntityRepositoryLib->findPublier(),
            5,
            0
        );

        return $this->render(
            $this->getTemplateFile($this->getcode($bookmark)),
            [
                'paragraph' => $bookmark,
                'bookmarks' => $bookmarks,
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
