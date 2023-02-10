<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\Chapter;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\Chapter as HistoryChapter;
use Labstag\Form\Admin\Paragraph\History\ChapterType;
use Labstag\Lib\ParagraphLib;

class ChapterParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return HistoryChapter::class;
    }

    public function getForm(): string
    {
        return ChapterType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('historychapter.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'historychapter';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(HistoryChapter $historychapter)
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $history = $routeParam['history'] ?? null;
        $chapter = $routeParam['chapter'] ?? null;
        $entityRepository = $this->getRepository(Chapter::class);
        $chapter = $entityRepository->findChapterByHistory($history, $chapter);
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

    /**
     * @return array<class-string<Layout>>
     */
    public function useIn(): array
    {
        return [
            Layout::class,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getPrevNext($chapter, $history): array
    {
        $chapters = $history->getchapters();
        $prev = null;
        $next = null;
        foreach ($chapters as $i => $row) {
            if ($row->getSlug() == $chapter->getSlug()) {
                $prev = $chapters[$i - 1] ?? null;
                $next = $chapters[$i + 1] ?? null;
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
