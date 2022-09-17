<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History\Liste;
use Labstag\Form\Admin\Paragraph\History\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;

class ListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Liste::class;
    }

    public function getForm()
    {
        return ListType::class;
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

    public function show(Liste $liste)
    {
        /** @var HistoryRepository $repository */
        $repository = $this->getRepository(History::class);

        $pagination = $this->paginator->paginate(
            $repository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('history/list'),
            [
                'pagination' => $pagination,
                'paragraph'  => $liste,
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
