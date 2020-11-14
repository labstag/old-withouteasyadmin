<?php

namespace Labstag\Service;

use Labstag\Repository\ConfigurationRepository;

class DataService
{

    private array $oauthActivated = [];

    private array $config = [];

    private ConfigurationRepository $repository;

    public function __construct(ConfigurationRepository $repository)
    {
        $this->repository = $repository;
        $this->setData();
    }

    public function getOauthActivated()
    {
        return $this->oauthActivated;
    }

    public function getConfig()
    {
        return $this->config;
    }

    private function setData()
    {
        if (count($this->config) != 0) {
            return;
        }

        $data   = $this->repository->findAll();
        $config = [];
        /** @var Configuration $row */
        foreach ($data as $row) {
            $key          = $row->getName();
            $value        = $row->getValue();
            $config[$key] = $value;
        }

        if (isset($config['oauth']) && is_array($config['oauth'])) {
            $oauth = [];
            $data  = $config['oauth'];
            foreach ($data as $row) {
                if (1 == $row['activate']) {
                    $type         = $row['type'];
                    $oauth[$type] = $row;
                }
            }

            $this->oauthActivated = $oauth;
        }

        $this->config = $config;
    }
}
