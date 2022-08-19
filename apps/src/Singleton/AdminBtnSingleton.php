<?php

namespace Labstag\Singleton;

use Labstag\Service\GuardService;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class AdminBtnSingleton
{

    protected $bouton;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected GuardService $guardService;

    protected bool $init = false;

    protected static $instance;

    protected RouterInterface $router;

    protected TokenStorageInterface $token;

    protected Environment $twig;

    protected function __construct()
    {
        $this->bouton = [];
    }

    public function add(
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

        $globals         = $this->twig->getGlobals();
        $modal           = $globals['modal'] ?? [];
        $modal['delete'] = true;
        $this->twig->addGlobal('modal', $modal);
        $code  = 'delete'.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'id'       => 'DeleteForm',
            'is'       => 'link-btnadmindelete',
            'token'    => $token,
            'redirect' => $this->router->generate($route['list']),
            'url'      => $this->router->generate(
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

    public function addBtnDestroy(
        object $entity,
        array $route,
        string $text = 'Destroy',
        array $routeParam = []
    )
    {
        $this->addBtnDestroyRestore('destroy', $entity, $route, $routeParam, $text);
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

        $globals        = $this->twig->getGlobals();
        $modal          = $globals['modal'] ?? [];
        $modal['empty'] = true;
        $this->twig->addGlobal('modal', $modal);
        $code  = 'empty';
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'is'       => 'link-btnadminempty',
            'token'    => $token,
            'redirect' => $this->router->generate($route['list']),
            'url'      => $this->router->generate(
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

    public function addBtnImport(string $route, string $text = 'Import'): self
    {
        if ('' == $route || !$this->isRouteEnable($route)) {
            return $this;
        }

        $this->add(
            'btn-admin-header-import',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
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

    public function addBtnRestore(
        object $entity,
        array $route,
        string $text = 'Restore',
        array $routeParam = []
    )
    {
        $this->addBtnDestroyRestore('restore', $entity, $route, $routeParam, $text);
    }

    public function addBtnSave(string $form, string $text = 'Sauvegarder'): self
    {
        $this->add(
            'btn-admin-header-save',
            $text,
            [
                'id'   => 'SaveForm',
                'form' => $form,
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

    public function addRestoreSelection(
        array $routes,
        string $code,
        string $title = 'Restaurer'
    )
    {
        $this->addBtnVider('restories', $routes, $code, $title);
    }

    public function addSupprimerSelection(
        array $routes,
        string $code,
        string $title = 'Supprimer'
    )
    {
        $this->addBtnVider('deleties', $routes, $code, $title);
    }

    public function addViderSelection(
        array $routes,
        string $code,
        string $title = 'Supprimer'
    )
    {
        $this->addBtnVider('empties', $routes, $code, $title);
    }

    public function get(): array
    {
        return $this->bouton;
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

    protected function addBtnVider(
        string $codemodal,
        array $routes,
        string $code,
        string $title = 'Restaurer',
    )
    {
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        if ($this->arrayKeyExistsRedirect($routes) || $this->arrayKeyExistsUrl($routes)) {
            return;
        }

        $globals           = $this->twig->getGlobals();
        $modal             = $globals['modal'] ?? [];
        $modal[$codemodal] = true;
        $this->twig->addGlobal('modal', $modal);
        $this->add(
            'btn-admin-header-'.$codemodal,
            $title,
            [
                'is'       => 'link-btnadmin'.$codemodal,
                'token'    => $token,
                'redirect' => $this->router->generate($routes['redirect']['href'], $routes['redirect']['params']),
                'url'      => $this->router->generate($routes['url']['href'], $routes['url']['params']),
            ]
        );
    }

    protected function classEntity($entity)
    {
        $class = str_replace('Labstag\\Entity\\', '', (string) $entity::class);

        return strtolower($class);
    }

    protected function isRouteEnable(string $route)
    {
        $token = $this->token->getToken();

        return $this->guardService->guardRoute($route, $token);
    }

    protected function isRoutesEnable(array $routes): bool
    {
        $return = true;
        foreach ($routes as $route) {
            $state = $this->isRouteEnable($route);
            if ($state) {
                continue;
            }

            $return = false;

            break;
        }

        return $return;
    }

    private function addBtnDestroyRestore($word, $entity, $route, $routeParam, $text)
    {
        if (!isset($route['list']) || !isset($route[$word])) {
            return $this;
        }

        $routes = [
            $route[$word],
            $route['list'],
        ];

        if (!$this->isRoutesEnable($routes)) {
            return $this;
        }

        $globals      = $this->twig->getGlobals();
        $modal        = $globals['modal'] ?? [];
        $modal[$word] = true;
        $this->twig->addGlobal('modal', $modal);
        $code  = $word.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'token'    => $token,
            'is'       => 'link-btnadmin'.$word,
            'redirect' => $this->router->generate($route['list']),
            'url'      => $this->router->generate(
                $route[$word],
                $routeParam
            ),
        ];

        $this->add(
            'btn-admin-header-'.$word,
            $text,
            $attr
        );
    }

    private function arrayKeyExistsRedirect($routes)
    {
        return !array_key_exists('redirect', $routes)
        || !array_key_exists('href', $routes['redirect'])
        || !array_key_exists('params', $routes['redirect'])
        || !$this->isRouteEnable($routes['redirect']['href']);
    }

    private function arrayKeyExistsUrl($routes)
    {
        return !array_key_exists('url', $routes)
        || !array_key_exists('href', $routes['url'])
        || !array_key_exists('params', $routes['url'])
        || !$this->isRouteEnable($routes['url']['href']);
    }
}
