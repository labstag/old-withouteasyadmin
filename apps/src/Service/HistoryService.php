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
        private readonly Environment $environment,
        protected HistoryRepository $historyRepository
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
        $history = $this->historyRepository->find($historyId);
        if (!$history instanceof History || (false == $all && !in_array('publie', (array) $history->getState()))) {
            return;
        }

        $collection = $this->getChapters($history, $all);
        if (0 == count($collection)) {
            return;
        }

        $html2Pdf  = $this->generateHistoryPdf($history, $collection);
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
        $html2Pdf->output($this->filename, 'F');
    }

    private function generateHistoryPdf(History $history, Collection $collection): Html2Pdf
    {
        $tmpfile = tmpfile();
        $data    = stream_get_meta_data($tmpfile);
        $html2Pdf     = new Html2Pdf();
        $html    = $this->environment->render(
            'pdf/history/index.html.twig',
            [
                'history'  => $history,
                'chapters' => $collection,
            ]
        );
        $html2Pdf->writeHTML($html);
        $html2Pdf->createIndex('Table des matières', 25, 12, false, true, 3);

        $file = $data['uri'].'.pdf';
        $html2Pdf->output($file, 'F');

        return $html2Pdf;
    }

    private function getChapters(History $history, bool $all): Collection
    {
        return $all ? $history->getChapters() : $history->getChaptersPublished();
    }
}
