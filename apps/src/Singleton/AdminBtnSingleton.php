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

    protected function __construct()
    {
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
}
