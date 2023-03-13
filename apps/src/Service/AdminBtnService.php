<?php

namespace Labstag\Service;

use Labstag\Interfaces\EntityInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class AdminBtnService
{

    protected array $bouton = [];

    public function __construct(
        protected Environment $twigEnvironment,
        protected RouterInterface $router,
        protected TokenStorageInterface $tokenStorage,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected GuardService $guardService
    )
    {
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
        EntityInterface $entity,
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

        $methods = get_class_methods($entity);
        if (!$this->isRoutesEnable($routes) || !in_array('getId', $methods)) {
            return $this;
        }

        $globals         = $this->twigEnvironment->getGlobals();
        $modal           = $globals['modal'] ?? [];
        $modal['delete'] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
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
        EntityInterface $entity,
        array $route,
        string $text = 'Destroy',
        array $routeParam = []
    ): void
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

        $globals        = $this->twigEnvironment->getGlobals();
        $modal          = $globals['modal'] ?? [];
        $modal['empty'] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
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
        EntityInterface $entity,
        array $route,
        string $text = 'Restore',
        array $routeParam = []
    ): void
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
    ): void
    {
        $this->addBtnVider('restories', $routes, $code, $title);
    }

    public function addSupprimerSelection(
        array $routes,
        string $code,
        string $title = 'Supprimer'
    ): void
    {
        $this->addBtnVider('deleties', $routes, $code, $title);
    }

    public function addViderSelection(
        array $routes,
        string $code,
        string $title = 'Supprimer'
    ): void
    {
        $this->addBtnVider('empties', $routes, $code, $title);
    }

    /**
     * @return array<mixed, array{icon: string, text: string, attr: mixed[]}>
     */
    public function get(): array
    {
        return $this->bouton;
    }

    protected function addBtnVider(
        string $codemodal,
        array $routes,
        string $code,
        string $title = 'Restaurer',
    ): void
    {
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        if ($this->arrayKeyExistsRedirect($routes) || $this->arrayKeyExistsUrl($routes)) {
            return;
        }

        $globals           = $this->twigEnvironment->getGlobals();
        $modal             = $globals['modal'] ?? [];
        $modal[$codemodal] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
        $this->add(
            'btn-admin-header-'.$codemodal,
            $title,
            [
                'is'       => 'link-btnadmin'.$codemodal,
                'token'    => $token,
                'redirect' => $this->router->generate(
                    $routes['redirect']['href'],
                    $routes['redirect']['params']
                ),
                'url'      => $this->router->generate(
                    $routes['url']['href'],
                    $routes['url']['params']
                ),
            ]
        );
    }

    protected function classEntity(EntityInterface $entity): string
    {
        $class = str_replace('Labstag\\Entity\\', '', (string) $entity::class);

        return strtolower($class);
    }

    protected function isRouteEnable(string $route): bool
    {
        $token = $this->tokenStorage->getToken();

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

    private function addBtnDestroyRestore(
        string $word,
        EntityInterface $entity,
        array $route,
        array $routeParam,
        string $text
    ): void
    {
        if (!isset($route['list']) || !isset($route[$word])) {
            return;
        }

        $routes = [
            $route[$word],
            $route['list'],
        ];

        if (!$this->isRoutesEnable($routes)) {
            return;
        }

        $globals      = $this->twigEnvironment->getGlobals();
        $modal        = $globals['modal'] ?? [];
        $modal[$word] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
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

    private function arrayKeyExistsRedirect(array $routes): bool
    {
        return !array_key_exists('redirect', $routes)
        || !array_key_exists('href', $routes['redirect'])
        || !array_key_exists('params', $routes['redirect'])
        || !$this->isRouteEnable($routes['redirect']['href']);
    }

    private function arrayKeyExistsUrl(array $routes): bool
    {
        return !array_key_exists('url', $routes)
        || !array_key_exists('href', $routes['url'])
        || !array_key_exists('params', $routes['url'])
        || !$this->isRouteEnable($routes['url']['href']);
    }
}
