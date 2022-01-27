<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Configuration;
use Labstag\Entity\Groupe;
use Labstag\Entity\Layout;
use Labstag\Entity\Menu;
use Labstag\Entity\Page;
use Labstag\Entity\Template;
use Labstag\Entity\User;
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
        protected PageRequestHandler $pageRH,
        protected MenuRequestHandler $menuRH,
        protected GroupeRequestHandler $groupeRH,
        protected ConfigurationRequestHandler $configurationRH,
        protected UserRequestHandler $userRH,
        protected TemplateRequestHandler $templateRH,
        protected EntityManagerInterface $entityManager,
        protected Environment $twig,
        protected CacheInterface $cache
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
        foreach ($groupes as $row) {
            $this->addGroupe($row);
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

    public function pages()
    {
        $pages = $this->getData('pages');
        foreach ($pages as $row) {
            $this->addPage($row, null);
        }
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
        $groupes = $this->getRepository(Groupe::class)->findAll();
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
            $this->entityManager->persist($child);
            $this->entityManager->flush();

            return;
        }

        $child->setName($attr['name']);
        if (isset($attr['data'])) {
            $child->setData($attr['data']);
        }

        $this->entityManager->persist($child);
        $this->entityManager->flush();
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
        $configuration = $this->getRepository(Configuration::class)->findOneBy($search);
        if (!$configuration instanceof Configuration) {
            $configuration = new Configuration();
        }

        $old = clone $configuration;
        $configuration->setName($key);
        $configuration->setValue($value);
        $this->configurationRH->handle($old, $configuration);
    }

    protected function addGroupe(
        string $row
    ): void
    {
        $search = ['code' => $row];
        $groupe = $this->getRepository(Groupe::class)->findOneBy($search);
        if ($groupe instanceof Groupe) {
            return;
        }

        $groupe = new Groupe();
        $old    = clone $groupe;
        $groupe->setCode($row);
        $groupe->setName($row);
        $this->groupeRH->handle($old, $groupe);
    }

    protected function addPage(array $row, ?Page $parent): void
    {
        $layout = $this->getRepository(Layout::class)->findOneBy(
            [
                'name' => $row['layout'],
            ]
        );
        $page   = new Page();
        $old    = clone $page;
        $page->setReflayout($layout);
        $page->setParent($parent);
        $page->setSlug($row['slug']);
        $page->setName($row['name']);
        $page->setFunction($row['template']);
        $page->setFront(isset($row['front']));
        $this->pageRH->handle($old, $page);
        if (isset($row['childs'])) {
            foreach ($row['childs'] as $child) {
                $this->addPage($child, $page);
            }
        }
    }

    protected function addTemplate(
        string $key,
        string $value
    ): void
    {
        $search   = ['code' => $key];
        $template = $this->getRepository(Template::class)->findOneBy($search);
        if ($template instanceof Template) {
            return;
        }

        $template = new Template();
        $old      = clone $template;
        $template->setName($value);
        $template->setCode($key);
        $htmlfile = 'tpl/mail-'.$key.'.html.twig';
        if (is_file('templates/'.$htmlfile)) {
            $template->setHtml($this->twig->render($htmlfile));
        }

        $txtfile = 'tpl/mail-'.$key.'.txt.twig';
        if (is_file('templates/'.$txtfile)) {
            $template->setText($this->twig->render($txtfile));
        }

        $this->templateRH->handle($old, $template);
    }

    protected function addUser(
        array $groupes,
        array $dataUser
    ): void
    {
        $search = [
            'username' => $dataUser['username'],
        ];
        $user   = $this->getRepository(User::class)->findOneBy($search);
        if ($user instanceof User) {
            return;
        }

        $this->userService->create($groupes, $dataUser);
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function saveMenu(string $key, array $childs): void
    {
        // $this->entityManager->getFilters()->disable('softdeleteable');
        $search = ['clef' => $key];
        $menu   = $this->getRepository(Menu::class)->findOneBy($search);
        if ($menu instanceof Menu) {
            $this->entityManager->remove($menu);
            $this->entityManager->flush();
        }

        $menu = new Menu();
        $menu->setPosition(0);
        $menu->setClef($key);
        $this->entityManager->persist($menu);
        $this->entityManager->flush();
        $indexChild = 0;
        foreach ($childs as $attr) {
            $this->addChild($indexChild, $menu, $attr);
            ++$indexChild;
        }
    }

    protected function setOauth(array $serverEnv, array &$data): void
    {
        $env   = $this->getEnv($serverEnv);
        $oauth = [];
        foreach ($env as $key => $val) {
            if (0 == substr_count($key, 'OAUTH_')) {
                continue;
            }

            $code    = str_replace('OAUTH_', '', $key);
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
