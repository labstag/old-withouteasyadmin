<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\GeoCode;
use Labstag\Repository\GeoCodeRepository;
use Labstag\RequestHandler\GeoCodeRequestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ZipArchive;

class LabstagGeocodeInstallCommand extends Command
{

    protected static $defaultName = 'labstag:geocode:install';

    protected HttpClientInterface $client;

    protected GeoCodeRepository $repository;

    protected EntityManagerInterface $entityManager;

    protected GeoCodeRequestHandler $geoCodeRH;

    public function __construct(
        GeoCodeRepository $repository,
        EntityManagerInterface $entityManager,
        HttpClientInterface $client,
        GeoCodeRequestHandler $geoCodeRH,
        string $name = null
    )
    {
        $this->geoCodeRH     = $geoCodeRH;
        $this->entityManager = $entityManager;
        $this->repository    = $repository;
        $this->client        = $client;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setDescription('Récupération des géocodes');
        $this->addArgument('country', InputArgument::REQUIRED, 'code pays');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $country     = $input->getArgument('country');

        if (empty($country)) {
            $inputOutput->note(
                sprintf(
                    'Argument countrie obligatoire: %s',
                    $country
                )
            );
            return COMMAND::FAILURE;
        }

        $country    = strtoupper($country);
        $file       = 'http://download.geonames.org/export/zip/'.$country.'.zip';
        $response   = $this->client->request(
            'GET',
            $file
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            $inputOutput->note(
                sprintf(
                    'Fichier %s introuvable',
                    $file
                )
            );
        }

        $content  = $response->getContent();
        $tempFile = tmpfile();
        $path     = stream_get_meta_data($tempFile)['uri'];
        file_put_contents($path, $content);
        $zip = new ZipArchive();
        if ($zip->open($path)) {
            $content = $zip->getFromName($country.'.txt');
            $csv     = str_getcsv($content, "\n");
            foreach ($csv as $line) {
                $row    = str_getcsv($line, "\t");
                $entity = $this->repository->findOneBy(
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
                $this->$this->geoCodeRH($old, $entity);
            }

            $zip->close();
        }

        return Command::SUCCESS;
    }
}
