<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\History;
use Labstag\Repository\HistoryRepository;
use setasign\Fpdi\Fpdi;
use Spipu\Html2Pdf\Html2Pdf;
use Twig\Environment;

class HistoryService
{

    private EntityManagerInterface $entityManager;

    private $filename;

    private HistoryRepository $historyRepo;

    private Environment $twig;

    public function __construct(
        HistoryRepository $historyRepo,
        Environment $twig,
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->twig          = $twig;
        $this->historyRepo   = $historyRepo;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function process(
        string $fileDirectory,
        string $historyId,
        bool $all
    )
    {
        $history = $this->historyRepo->find($historyId);
        if (!$history instanceof History) {
            return;
        }

        if (false == $all && !in_array('publie', $history->getState())) {
            return;
        }

        $pdf  = $this->generateHistoryPdf($history, $all);
        $path = sprintf(
            '%s/%s',
            $fileDirectory,
            'history'
        );
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $this->filename = sprintf(
            '%s/%s.pdf',
            $path,
            $history->getSlug()
        );
        $pdf->output($this->filename, 'F');
    }

    private function addPagePdf(&$fpdi, string $file): int
    {
        $pageCount = $fpdi->setSourceFile($file);
        for ($pageNo = 1; $pageNo <= $pageCount; ++$pageNo) {
            $templateId = $fpdi->importPage($pageNo);
            $fpdi->addPage();
            $fpdi->useTemplate($templateId);
        }

        return $pageCount;
    }

    private function addSummary(History $history, array $info)
    {
        $tmpfile = tmpfile();
        $data    = stream_get_meta_data($tmpfile);
        $pdf     = new Html2Pdf();
        $html    = $this->twig->render(
            'pdf/history/summary.html.twig',
            [
                'history' => $history,
                'info'    => $info,
            ]
        );
        $pdf->writeHTML($html);
        $file = $data['uri'].'.pdf';
        $pdf->output($file, 'F');

        return $file;
    }

    private function addTitle(History $history)
    {
        $tmpfile = tmpfile();
        $data    = stream_get_meta_data($tmpfile);
        $pdf     = new Html2Pdf();
        $html    = $this->twig->render(
            'pdf/history/title.html.twig',
            ['history' => $history]
        );
        $pdf->writeHTML($html);
        $file = $data['uri'].'.pdf';
        $pdf->output($file, 'F');

        return $file;
    }

    private function generateChapterPdf(History $history, bool $all)
    {
        $files = [];
        foreach ($history->getChapters() as $chapter) {
            if (false == $all && !in_array('publie', $chapter->getStatus())) {
                continue;
            }

            $tmpfile = tmpfile();
            $data    = stream_get_meta_data($tmpfile);
            $pdf     = new Html2Pdf();
            $html    = $this->twig->render(
                'pdf/history/content.html.twig',
                ['chapter' => $chapter]
            );
            $pdf->writeHTML($html);
            $file = $data['uri'].'.pdf';
            $pdf->output($file, 'F');
            $pages = $this->getCountPagesFile($file);
            $chapter->setPages($pages);
            $this->entityManager->persist($chapter);
            $files[$data['uri'].'.pdf'] = [
                'file' => $file,
                'name' => $chapter->getName(),
            ];
        }

        $this->entityManager->flush();

        return $files;
    }

    private function generateHistoryPdf(History $history, bool $all)
    {
        $files = $this->generateChapterPdf($history, $all);
        $info  = $this->getInfoPosition($files);
        array_unshift(
            $files,
            [
                'file' => $this->addTitle($history),
            ],
            [
                'file' => $this->addSummary($history, $info),
            ]
        );
        $pdf = new Fpdi();
        $pdf->setAuthor($history->getRefuser()->__toString());
        $pdf->setCreator($history->getRefuser()->__toString());
        $pdf->setTitle($history->getName());
        $pages = 0;
        foreach ($files as $row) {
            $pages += $this->addPagePdf($pdf, $row['file']);
        }

        $history->setPages($pages);
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        return $pdf;
    }

    private function getCountPagesFile(string $file): int
    {
        $fpdi = new Fpdi();

        return $fpdi->setSourceFile($file);
    }

    private function getInfoPosition(array $files)
    {
        $fpdi     = new Fpdi();
        $info     = [];
        $position = 1;
        foreach ($files as $row) {
            $position = $position + $fpdi->setSourceFile($row['file']);
            $info[]   = [
                'name'     => $row['name'],
                'file'     => $row['file'],
                'position' => $position,
            ];
        }

        return $info;
    }
}
