<?php
namespace Labstag\Singleton;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class AdminBtnSingleton
{

    protected static $instance = null;

    protected bool $init = false;

    protected Environment $twig;

    protected RouterInterface $router;

    protected CsrfTokenManagerInterface $csrfTokenManager;

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
        CsrfTokenManagerInterface $csrfTokenManager
    )
    {
        $this->twig             = $twig;
        $this->router           = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->init             = true;
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
        $this->twig->addGlobal(
            'modalRestore',
            true
        );
        $code  = 'restore' . $entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'data-toggle' => 'modal',
            'data-token'  => $token,
            'data-target' => '#restoreModal',
            'is'          => 'link-btnadminrestore',
        ];
        if (isset($route['list'])) {
            $attr['data-redirect'] = $this->router->generate(
                $route['list'],
                [
                    'entity' => $this->classEntity($entity),
                ]
            );
        }

        if (isset($route['restore'])) {
            $attr['data-url'] = $this->router->generate(
                $route['restore'],
                $routeParam
            );
        }

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
        $this->twig->addGlobal(
            'modalDestroy',
            true
        );
        $code  = 'destroy' . $entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'data-toggle' => 'modal',
            'data-token'  => $token,
            'data-target' => '#destroyModal',
            'is'          => 'link-btnadmindestroy',
        ];
        if (isset($route['list'])) {
            $attr['data-redirect'] = $this->router->generate($route['list']);
        }

        if (isset($route['destroy'])) {
            $attr['data-url'] = $this->router->generate(
                $route['destroy'],
                $routeParam
            );
        }

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
        $attr = [];
        if ($route != '') {
            $attr['href'] = $this->router->generate($route, $routeParam);
        }

        $this->add(
            'btn-admin-header-guard',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnEdit(
        string $route,
        string $text = 'Editer',
        array $routeParam = []
    ): self
    {
        $attr = [];
        if ($route != '') {
            $attr['href'] = $this->router->generate($route, $routeParam);
        }

        $this->add(
            'btn-admin-header-edit',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnShow(
        string $route,
        string $text = 'Show',
        array $routeParam = []
    ): self
    {
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
        $this->twig->addGlobal(
            'modalDelete',
            true
        );
        $code  = 'delete' . $entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'id'          => 'DeleteForm',
            'is'          => 'link-btnadmindelete',
            'data-token'  => $token,
            'data-toggle' => 'modal',
            'data-target' => '#deleteModal',
        ];
        if (isset($route['list'])) {
            $attr['data-redirect'] = $this->router->generate($route['list']);
        }

        if (isset($route['delete'])) {
            $attr['data-url'] = $this->router->generate(
                $route['delete'],
                $routeParam
            );
        }

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
        $this->twig->addGlobal(
            'modalEmpty',
            true
        );
        $code  = 'empty';
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'is'          => 'link-btnadminempty',
            'data-toggle' => 'modal',
            'data-token'  => $token,
            'data-target' => '#emptyModal',
        ];
        if (isset($route['list'])) {
            $attr['data-redirect'] = $this->router->generate($route['list']);
        }

        if (isset($route['empty'])) {
            $attr['data-url'] = $this->router->generate(
                $route['empty'],
                ['entity' => $entity]
            );
        }

        $this->add(
            'btn-admin-header-empty',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnList(string $route, string $text = 'Liste'): self
    {
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
