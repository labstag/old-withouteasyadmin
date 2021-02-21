<?php

namespace Labstag\Service;

use Labstag\RequestHandler\ConfigurationRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\RequestHandler\MenuRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Symfony\Component\Dotenv\Dotenv;

class InstallService
{

    protected MenuRequestHandler $menuRH;

    protected GroupeRequestHandler $groupeRH;

    protected ConfigurationRequestHandler $configurationRH;

    protected TemplateRequestHandler $templateRH;

    public function __construct(
        MenuRequestHandler $menuRH,
        GroupeRequestHandler $groupeRH,
        ConfigurationRequestHandler $configurationRH,
        TemplateRequestHandler $templateRH
    )
    {
        $this->menuRH          = $menuRH;
        $this->groupeRH        = $groupeRH;
        $this->configurationRH = $configurationRH;
        $this->templateRH      = $templateRH;
    }

    public function getData($file)
    {
        $file = __DIR__.'/../../json/'.$file.'.json';
        $data = [];
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        return $data;
    }

    public function getEnv()
    {
        $file   = __DIR__.'/../../.env';
        $data   = [];
        $dotenv = new Dotenv();
        if (is_file($file)) {
            $data = $dotenv->parse(file_get_contents($file));
        }

        ksort($data);

        return $data;
    }

    public function menuadmin()
    {
        $data = $this->getData('menuadmin');
        dump($data);
    }

    public function menuadminprofil()
    {
        $data = $this->getData('menuadminprofil');
        dump($data);
    }

    public function group()
    {
        $data = $this->getData('group');
        dump($data);
    }

    public function config()
    {
        $data = $this->getData('config');
        dump($data);
    }

    public function templates()
    {
        $data = $this->getData('template');
        dump($data);
    }

    public function all()
    {
        $this->menuadmin();
        $this->menuadminprofil();
        $this->group();
        $this->config();
        $this->templates();
    }
}
