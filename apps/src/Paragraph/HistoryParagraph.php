<?php

namespace Labstag\Paragraph;

use Labstag\Entity\History as EntityHistory;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History;
use Labstag\Form\Admin\Paragraph\HistoryType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;

class HistoryParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return History::class;
    }

    public function getForm()
    {
        return HistoryType::class;
    }

    public function getName()
    {
        return $this->translator->trans('history.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'history';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(History $history)
    {
        /** @var HistoryRepository $repository */
        $repository = $this->getRepository(EntityHistory::class);
        $histories  = $repository->findPublier();

        return $this->render(
            $this->getParagraphFile('history'),
            [
                'histories' => $histories,
                'paragraph' => $history,
            ]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
