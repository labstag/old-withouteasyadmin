<?php

namespace Labstag\Paragraph;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\History as EntityHistory;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History;
use Labstag\Form\Admin\Paragraph\HistoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;

class HistoryParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return History::class;
    }

    public function getForm(): string
    {
        return HistoryType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('history.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'history';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(History $history): Response
    {
        /** @var HistoryRepository $repository */
        $repository = $this->getRepository(EntityHistory::class);
        $histories  = $repository->getLimitOffsetResult($repository->findPublier(), 5, 0);

        return $this->render(
            $this->getParagraphFile('history'),
            [
                'histories' => $histories,
                'paragraph' => $history,
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
