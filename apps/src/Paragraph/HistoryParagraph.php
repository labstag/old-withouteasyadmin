<?php

namespace Labstag\Paragraph;

use Labstag\Entity\History as EntityHistory;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History;
use Labstag\Form\Admin\Paragraph\HistoryType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Response;

class HistoryParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'history';
    }

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
        /** @var HistoryRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(EntityHistory::class);
        $histories = $serviceEntityRepositoryLib->getLimitOffsetResult($serviceEntityRepositoryLib->findPublier(), 5, 0);

        return $this->render(
            $this->getTemplateFile($this->getcode($history)),
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
