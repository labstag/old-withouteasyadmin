<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\Chapter;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\Chapter as HistoryChapter;
use Labstag\Form\Admin\Paragraph\History\ChapterType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\ChapterRepository;

class ChapterParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return HistoryChapter::class;
    }

    public function getForm()
    {
        return ChapterType::class;
    }

    public function getName()
    {
        return $this->translator->trans('historychapter.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'historychapter';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(HistoryChapter $historychapter)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $history    = $routeParam['history'] ?? null;
        $chapter    = $routeParam['chapter'] ?? null;
        /** @var ChapterRepository $repository */
        $chapterRepo = $this->getRepository(Chapter::class);
        $chapter     = $chapterRepo->findChapterByHistory($history, $chapter);
        if (!$chapter instanceof Chapter) {
            return;
        }

        $prevnext = $this->getPrevNext($chapter, $chapter->getRefhistory());

        return $this->render(
            $this->getParagraphFile('history/chapter'),
            [
                'prev'      => $prevnext['prev'],
                'next'      => $prevnext['next'],
                'chapter'   => $chapter,
                'history'   => $chapter->getRefhistory(),
                'paragraph' => $historychapter,
            ]
        );
    }

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }

    private function getPrevNext($chapter, $history)
    {
        $chapters = $history->getchapters();
        $prev     = null;
        $next     = null;
        foreach ($chapters as $i => $row) {
            if ($row->getSlug() == $chapter->getSlug()) {
                $prev    = $chapters[$i - 1] ?? null;
                $next    = $chapters[$i + 1] ?? null;
                $chapter = $row;

                break;
            }
        }

        return [
            'prev' => $prev,
            'next' => $next,
        ];
    }
}
