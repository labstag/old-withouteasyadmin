<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\GeoCode;
use Labstag\Repository\GeoCodeRepository;
use Labstag\RequestHandler\GeoCodeRequestHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ZipArchive;

class GeocodeService
{
    /**
     * @var int
     */
    final public const HTTP_OK = 200;

    public function __construct(
        protected HttpClientInterface $httpClient,
        protected EntityManagerInterface $entityManager,
        protected GeoCodeRequestHandler $geoCodeRequestHandler,
        protected GeoCodeRepository $geoCodeRepository
    )
    {
    }

    public function add(array $row): void
    {
        $entity = $this->geoCodeRepository->findOneBy(
            [
                'countryCode' => $row[0],
                'postalCode'  => $row[1],
                'placeName'   => $row[2],
            ]
        );
        if (!($entity instanceof GeoCode)) {
            $entity = new GeoCode();
            $entity->setCountryCode($row[0]);
            $entity->setPostalCode($row[1]);
            $entity->setPlaceName($row[2]);
        }

        $old = clone $entity;
        $entity->setStateName($row[3]);
        $entity->setStateCode($row[4]);
        $entity->setProvinceName($row[5]);
        $entity->setProvinceCode($row[6]);
        $entity->setCommunityName($row[7]);
        $entity->setCommunityCode($row[8]);
        $entity->setLatitude($row[9]);
        $entity->setLongitude($row[10]);
        $entity->setAccuracy((int) $row[11]);

        $this->geoCodeRequestHandler->handle($old, $entity);
    }

    public function csv(string $country): array
    {
        $country  = strtoupper($country);
        $file     = 'http://download.geonames.org/export/zip/'.$country.'.zip';
        $response = $this->httpClient->request(
            'GET',
            $file
        );
        $statusCode = $response->getStatusCode();
        if (self::HTTP_OK != $statusCode) {
            return [];
        }

        $content = $response->getContent();
        /** @var resource $tempFile */
        $tempFile = tmpfile();
        $path     = stream_get_meta_data($tempFile)['uri'];
        file_put_contents($path, $content);
        $zipArchive = new ZipArchive();
        if (!$zipArchive->open($path)) {
            return [];
        }

        $content = (string) $zipArchive->getFromName($country.'.txt');
        $csv     = str_getcsv($content, "\n");
        $zipArchive->close();

        return $csv;
    }

    public function tables(array $csv): array
    {
        $data = [];
        foreach ($csv as $line) {
            $row    = str_getcsv((string) $line, "\t");
            $data[] = $row;
        }

        return $data;
    }
}
