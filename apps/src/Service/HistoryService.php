<?php

namespace Labstag\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\History;
use Labstag\Repository\HistoryRepository;
use Spipu\Html2Pdf\Html2Pdf;
use Twig\Environment;

class HistoryService
{

    private $filename;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        private readonly Environment $twig,
        protected HistoryRepository $historyRepo
    )
    {
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
        if (!$history instanceof History || (false == $all && !in_array('publie', (array) $history->getState()))) {
            return;
        }

        $dataChapters = $this->getChapters($history, $all);
        if (0 == count($dataChapters)) {
            return;
        }

        $pdf  = $this->generateHistoryPdf($history, $dataChapters);
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
            $history->getSlug().($all ? '-all' : '')
        );
        $pdf->output($this->filename, 'F');
    }

    private function generateHistoryPdf(History $history, Collection $dataChapters): Html2Pdf
    {
        $tmpfile = tmpfile();
        $data    = stream_get_meta_data($tmpfile);
        $pdf     = new Html2Pdf();
        $html    = $this->twig->render(
            'pdf/history/index.html.twig',
            [
                'history'  => $history,
                'chapters' => $dataChapters,
            ]
        );
        $pdf->writeHTML($html);
        $pdf->createIndex('Table des matières', 25, 12, false, true, 3);

        $file = $data['uri'].'.pdf';
        $pdf->output($file, 'F');

        return $pdf;
    }

    private function getChapters(History $history, bool $all): Collection
    {
        return $all ? $history->getChapters() : $history->getChaptersPublished();
    }
}
