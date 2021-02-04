<?php

namespace Labstag\Singleton;

use Labstag\Service\GuardService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class AdminBtnSingleton
{

    protected static $instance = null;

    protected bool $init = false;

    protected Environment $twig;

    protected RouterInterface $router;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected GuardService $guardService;

    protected TokenStorageInterface $token;

    protected $bouton;

    protected function __construct()
    {
        $this->bouton = [];
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminBtnSingleton();
        }

        return self::$instance;
    }

    public function isInit()
    {
        return $this->init;
    }

    public function setConf(
        Environment $twig,
        RouterInterface $router,
        TokenStorageInterface $token,
        CsrfTokenManagerInterface $csrfTokenManager,
        GuardService $guardService
    )
    {
        $this->twig             = $twig;
        $this->router           = $router;
        $this->token            = $token;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->guardService     = $guardService;
        $this->init             = true;
    }

    protected function isRouteEnable(string $route)
    {
        $token = $this->token->getToken();

        return $this->guardService->guardRoute($route, $token);
    }

    protected function isRoutesEnable(array $routes)
    {
        foreach ($routes as $route) {
            $state = $this->isRouteEnable($route);
            if (!$state) {
                return false;
            }
        }
    }

    protected function add(
        string $icon,
        string $text,
        array $attr = []
    ): self
    {
        if (!isset($attr['href'])) {
            $attr['href'] = '#';
        }

        $attr = array_merge(
            $attr,
            ['title' => $text]
        );

        $this->bouton[] = [
            'icon' => $icon,
            'text' => $text,
            'attr' => $attr,
        ];

        return $this;
    }

    protected function classEntity($entity)
    {
        $class = get_class($entity);

        $class = str_replace('Labstag\\Entity\\', '', $class);

        return strtolower($class);
    }

    public function addBtnRestore(
        object $entity,
        array $route,
        string $text = 'Restore',
        array $routeParam = []
    ): self
    {
        if (!isset($route['restore']) || !isset($route['list'])) {
            return $this;
        }

        $routes = [
            $route['restore'],
            $route['list'],
        ];
        if (!$this->isRoutesEnable($routes)) {
            return $this;
        }

        $this->twig->addGlobal(
            'modalRestore',
            true
        );
        $code  = 'restore'.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'data-toggle'   => 'modal',
            'data-token'    => $token,
            'data-target'   => '#restoreModal',
            'is'            => 'link-btnadminrestore',
            'data-redirect' => $this->router->generate(
                $route['list'],
                [
                    'entity' => $this->classEntity($entity),
                ]
            ),
            'data-url'      => $this->router->generate(
                $route['restore'],
                $routeParam
            ),
        ];

        $this->add(
            'btn-admin-header-restore',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnDestroy(
        object $entity,
        array $route,
        string $text = 'Destroy',
        array $routeParam = []
    ): self
    {
        if (!isset($route['list']) || !isset($route['destroy'])) {
            return $this;
        }

        $routes = [
            $route['destroy'],
            $route['list'],
        ];
        if (!$this->isRoutesEnable($routes)) {
            return $this;
        }

        $this->twig->addGlobal(
            'modalDestroy',
            true
        );
        $code  = 'destroy'.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'data-toggle'   => 'modal',
            'data-token'    => $token,
            'data-target'   => '#destroyModal',
            'is'            => 'link-btnadmindestroy',
            'data-redirect' => $this->router->generate($route['list']),
            'data-url'      => $this->router->generate(
                $route['destroy'],
                $routeParam
            ),
        ];

        $this->add(
            'btn-admin-header-destroy',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnGuard(
        string $route,
        string $text = 'Editer',
        array $routeParam = []
    ): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-guard',
            $text,
            [
                'href' => $this->router->generate($route, $routeParam),
            ]
        );

        return $this;
    }

    public function addBtnEdit(
        string $route,
        string $text = 'Editer',
        array $routeParam = []
    ): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-edit',
            $text,
            [
                'href' => $this->router->generate($route, $routeParam),
            ]
        );

        return $this;
    }

    public function addBtnShow(
        string $route,
        string $text = 'Show',
        array $routeParam = []
    ): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-show',
            $text,
            [
                'href' => $this->router->generate($route, $routeParam),
            ]
        );

        return $this;
    }

    public function addBtnDelete(
        object $entity,
        array $route,
        string $text = 'Supprimer',
        array $routeParam = []
    ): self
    {
        if (!isset($route['list']) || !isset($route['delete'])) {
            return $this;
        }

        $routes = [
            $route['list'],
            $route['delete'],
        ];

        if (!$this->isRoutesEnable($routes)) {
            return $this;
        }

        $this->twig->addGlobal(
            'modalDelete',
            true
        );
        $code  = 'delete'.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'id'            => 'DeleteForm',
            'is'            => 'link-btnadmindelete',
            'data-token'    => $token,
            'data-toggle'   => 'modal',
            'data-target'   => '#deleteModal',
            'data-redirect' => $this->router->generate($route['list']),
            'data-url'      => $this->router->generate(
                $route['delete'],
                $routeParam
            ),
        ];

        $this->add(
            'btn-admin-header-delete',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnSave(string $form, string $text = 'Sauvegarder'): self
    {
        $this->add(
            'btn-admin-header-save',
            $text,
            [
                'id'        => 'SaveForm',
                'data-form' => $form,
            ]
        );

        return $this;
    }

    public function addBtnNew(string $route, string $text = 'Nouveau'): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-new',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
        );

        return $this;
    }

    public function addBtnTrash(string $route, string $text = 'Corbeille'): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-trash',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
        );

        return $this;
    }

    public function addBtnEmpty(array $route, string $entity, string $text = 'Vider'): self
    {
        if (!isset($route['list']) || !isset($route['empty'])) {
            return $this;
        }

        $routes = [
            $route['list'],
            $route['empty'],
        ];
        if (!$this->isRoutesEnable($routes)) {
            return $this;
        }

        $this->twig->addGlobal(
            'modalEmpty',
            true
        );
        $code  = 'empty';
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'is'            => 'link-btnadminempty',
            'data-toggle'   => 'modal',
            'data-token'    => $token,
            'data-target'   => '#emptyModal',
            'data-redirect' => $this->router->generate($route['list']),
            'data-url'      => $this->router->generate(
                $route['empty'],
                ['entity' => $entity]
            ),
        ];

        $this->add(
            'btn-admin-header-empty',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnList(string $route, string $text = 'Liste'): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-list',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
        );

        return $this;
    }

    public function get(): array
    {
        return $this->bouton;
    }
}
