<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Configuration;
use Labstag\Entity\Groupe;
use Labstag\Entity\Menu;
use Labstag\Entity\Template;
use Labstag\Entity\User;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\MenuRepository;
use Labstag\Repository\TemplateRepository;
use Labstag\Repository\UserRepository;
use Labstag\RequestHandler\ConfigurationRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\RequestHandler\MenuRequestHandler;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class InstallService
{
    public function __construct(
        protected OauthService $oauthService,
        protected UserService $userService,
        protected PageRequestHandler $pageRequestHandler,
        protected MenuRequestHandler $menuRequestHandler,
        protected GroupeRequestHandler $groupeRequestHandler,
        protected ConfigurationRequestHandler $configurationRequestHandler,
        protected UserRequestHandler $userRequestHandler,
        protected TemplateRequestHandler $templateRequestHandler,
        protected EntityManagerInterface $entityManager,
        protected Environment $environment,
        protected CacheInterface $cache,
        protected GroupeRepository $groupeRepository,
        protected MenuRepository $menuRepository,
        protected ConfigurationRepository $configurationRepository,
        protected TemplateRepository $templateRepository,
        protected UserRepository $userRepository
    )
    {
    }

    public function config($serverEnv)
    {
        $config = $this->getData('config');
        $this->setOauth($serverEnv, $config);
        foreach ($config as $key => $row) {
            $this->addConfig($key, $row);
        }

        $this->cache->delete('configuration');
    }

    public function getData($file)
    {
        $file = __DIR__.'/../../json/'.$file.'.json';
        $data = [];
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        }

        return $data;
    }

    public function getEnv($serverEnv)
    {
        $file   = __DIR__.'/../../.env';
        $data   = [];
        $dotenv = new Dotenv();
        if (is_file($file)) {
            $data = $dotenv->parse(file_get_contents($file));
        }

        $data = array_merge($serverEnv, $data);
        ksort($data);

        return $data;
    }

    public function group()
    {
        $groupes = $this->getData('group');
        foreach ($groupes as $groupe) {
            $this->addGroupe($groupe);
        }
    }

    public function menuadmin()
    {
        $childs = $this->getData('menuadmin');
        $this->saveMenu('admin', $childs);
    }

    public function menuadminprofil()
    {
        $childs = $this->getData('menuadminprofil');
        $this->saveMenu('admin-profil', $childs);
    }

    public function templates()
    {
        $templates = $this->getData('template');
        foreach ($templates as $key => $row) {
            $this->addTemplate($key, $row);
        }
    }

    public function users()
    {
        $users   = $this->getData('user');
        $groupes = $this->groupeRepository->findAll();
        foreach ($users as $user) {
            $this->addUser($groupes, $user);
        }
    }

    protected function addChild(int $index, Menu $menu, array $attr): void
    {
        $child = new Menu();
        $child->setPosition($index);
        $child->setParent($menu);
        if (isset($attr['separator'])) {
            $child->setSeparateur(true);
            $this->menuRepository->add($child);

            return;
        }

        $child->setName($attr['name']);
        if (isset($attr['data'])) {
            $child->setData($attr['data']);
        }

        $this->menuRepository->add($child);
        if (isset($attr['childs'])) {
            $indexChild = 0;
            foreach ($attr['childs'] as $attrChild) {
                $this->addChild($indexChild, $child, $attrChild);
                ++$indexChild;
            }
        }
    }

    protected function addConfig(
        string $key,
        $value
    ): void
    {
        $search        = ['name' => $key];
        $configuration = $this->configurationRepository->findOneBy($search);
        if (!$configuration instanceof Configuration) {
            $configuration = new Configuration();
        }

        $old = clone $configuration;
        $configuration->setName($key);
        $configuration->setValue($value);

        $this->configurationRequestHandler->handle($old, $configuration);
    }

    protected function addGroupe(
        string $row
    ): void
    {
        $search = ['code' => $row];
        $groupe = $this->groupeRepository->findOneBy($search);
        if ($groupe instanceof Groupe) {
            return;
        }

        $groupe = new Groupe();
        $old    = clone $groupe;
        $groupe->setCode($row);
        $groupe->setName($row);

        $this->groupeRequestHandler->handle($old, $groupe);
    }

    protected function addTemplate(
        string $key,
        string $value
    ): void
    {
        $search   = ['code' => $key];
        $template = $this->templateRepository->findOneBy($search);
        if ($template instanceof Template) {
            return;
        }

        $template = new Template();
        $old      = clone $template;
        $template->setName($value);
        $template->setCode($key);

        $htmlfile = 'tpl/mail-'.$key.'.html.twig';
        if (is_file('templates/'.$htmlfile)) {
            $template->setHtml($this->environment->render($htmlfile));
        }

        $txtfile = 'tpl/mail-'.$key.'.txt.twig';
        if (is_file('templates/'.$txtfile)) {
            $template->setText($this->environment->render($txtfile));
        }

        $this->templateRequestHandler->handle($old, $template);
    }

    protected function addUser(
        array $groupes,
        array $dataUser
    ): void
    {
        $search = [
            'username' => $dataUser['username'],
        ];
        $user   = $this->userRepository->findOneBy($search);
        if ($user instanceof User) {
            return;
        }

        $this->userService->create($groupes, $dataUser);
    }

    protected function saveMenu(string $key, array $childs): void
    {
        // $this->entityManager->getFilters()->disable('softdeleteable');
        $search = ['clef' => $key];
        $menu   = $this->menuRepository->findOneBy($search);
        if ($menu instanceof Menu) {
            $this->menuRepository->remove($menu);
        }

        $menu = new Menu();
        $menu->setPosition(0);
        $menu->setClef($key);

        $this->menuRepository->add($menu);
        $indexChild = 0;
        foreach ($childs as $child) {
            $this->addChild($indexChild, $menu, $child);
            ++$indexChild;
        }
    }

    protected function setOauth(array $serverEnv, array &$data): void
    {
        $env   = $this->getEnv($serverEnv);
        $oauth = [];
        foreach ($env as $key => $val) {
            if (0 == substr_count((string) $key, 'OAUTH_')) {
                continue;
            }

            $code    = str_replace('OAUTH_', '', (string) $key);
            $code    = strtolower($code);
            $explode = explode('_', $code);
            $type    = $explode[0];
            $key     = $explode[1];
            if (!isset($oauth[$type])) {
                $activate = $this->oauthService->getActivedProvider($type);

                $oauth[$type] = [
                    'activate' => $activate,
                    'type'     => $type,
                ];
            }

            $oauth[$type][$key] = $val;
        }

        // @var mixed $row
        foreach ($oauth as $row) {
            $data['oauth'][] = $row;
        }
    }
}
