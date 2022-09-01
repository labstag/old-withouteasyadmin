<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History;
use Labstag\Form\Admin\Paragraph\HistoryType;
use Labstag\Lib\ParagraphLib;

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

    public function show(History $history)
    {
        return $this->render(
            $this->getParagraphFile('history'),
            ['paragraph' => $history]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
