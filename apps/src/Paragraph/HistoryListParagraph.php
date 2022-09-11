<?php

namespace Labstag\Paragraph;

use Labstag\Entity\History as EntityHistory;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\HistoryList;
use Labstag\Form\Admin\Paragraph\HistoryListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\HistoryListRepository;

class HistoryListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return HistoryList::class;
    }

    public function getForm()
    {
        return HistoryListType::class;
    }

    public function getName()
    {
        return $this->translator->trans('historylist.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'historylist';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(HistoryList $historylist)
    {
        /** @var HistoryListRepository $repository */
        $repository = $this->getRepository(EntityHistory::class);

        $pagination = $this->paginator->paginate(
            $repository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('historylist'),
            [
                'pagination' => $pagination,
                'paragraph'  => $historylist,
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
