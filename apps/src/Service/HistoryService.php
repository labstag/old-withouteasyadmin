<?php

namespace Labstag\Service;

use Labstag\Entity\History;
use Labstag\Repository\HistoryRepository;
use setasign\Fpdi\Fpdi;
use Spipu\Html2Pdf\Html2Pdf;
use Twig\Environment;

class HistoryService
{

    private $filename;

    private HistoryRepository $historyRepo;

    private Environment $twig;

    public function __construct(
        HistoryRepository $historyRepo,
        Environment $twig
    )
    {
        $this->twig        = $twig;
        $this->historyRepo = $historyRepo;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function process(
        string $fileDirectory,
        string $historyId
    )
    {
        $history = $this->historyRepo->find($historyId);
        if (!$history instanceof History) {
            return;
        }

        $pdf  = $this->generateHistoryPdf($history);
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

    private function addPagePdf(&$fpdi, string $fichier)
    {
        $pageCount = $fpdi->setSourceFile($fichier);
        for ($pageNo = 1; $pageNo <= $pageCount; ++$pageNo) {
            $templateId = $fpdi->importPage($pageNo);
            $fpdi->addPage();
            $fpdi->useTemplate($templateId);
        }
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

    private function generateChapterPdf(History $history)
    {
        $files = [];
        foreach ($history->getChapters() as $chapter) {
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
            $files[$data['uri'].'.pdf'] = [
                'file' => $file,
                'name' => $chapter->getName(),
            ];
        }

        return $files;
    }

    private function generateHistoryPdf(History $history)
    {
        $files = $this->generateChapterPdf($history);
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
        foreach ($files as $row) {
            $this->addPagePdf($pdf, $row['file']);
        }

        return $pdf;
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
