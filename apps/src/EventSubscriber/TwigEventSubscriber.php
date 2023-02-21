<?php

namespace Labstag\EventSubscriber;

use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class TwigEventSubscriber extends EventSubscriberLib
{
    /**
     * @var string
     */
    final public const ADMIN_CONTROLLER = '/(Controller\\\Admin)/';

    /**
     * @var string[]
     */
    final public const ERROR_CONTROLLER = [
        'error_controller',
        'error_controller::preview',
    ];

    /**
     * @var string
     */
    final public const LABSTAG_CONTROLLER = '/(Labstag)/';

    /**
     * @return array<class-string<ControllerEvent>, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent'];
    }

    public function onControllerEvent(ControllerEvent $controllerEvent): void
    {
        $this->setLoginPage($controllerEvent);
        $this->setConfig($controllerEvent);
    }

    protected function isStateConfig(ControllerEvent $controllerEvent)
    {
        $controller = $controllerEvent->getRequest()->attributes->get('_controller');
        $matches = [];
        preg_match(self::LABSTAG_CONTROLLER, (string) $controller, $matches);

        return 0 == count($matches) && !in_array($controller, self::ERROR_CONTROLLER);
    }

    protected function setConfig(ControllerEvent $controllerEvent): void
    {
        $this->setConfigFavicon();
        $this->setConfigCanonical();
        if ($this->isStateConfig($controllerEvent)) {
            return;
        }

        $globals = $this->environment->getGlobals();
        $config = $globals['config'] ?? $this->dataService->getConfig();
        $this->setConfigMeta($config);
        $this->setConfigTac($config);
        $this->setFormatDatetime($config);
        $this->environment->AddGlobal('config', $config);
    }

    protected function setConfigCanonical()
    {
        $globals = $this->environment->getGlobals();
        $canonical = $globals['canonical'] ?? $this->request->getUri();
        $this->environment->AddGlobal('canonical', $canonical);
    }

    protected function setConfigFavicon()
    {
        $favicon = $this->attachmentRepository->getFavicon();
        $this->environment->AddGlobal('favicon', $favicon);
    }

    protected function setConfigMeta($config)
    {
        $config['meta'] = array_key_exists('meta', $config) ? $config['meta'] : [];
        $config['meta'] = $this->frontService->configMeta($config, $config['meta']);
        $this->frontService->setMetatags($config['meta']);
    }

    protected function setConfigTac(array $config): void
    {
        if (!array_key_exists('tarteaucitron', $config)) {
            return;
        }

        $tab = [
            'groupServices',
            'showAlertSmall',
            'cookieslist',
            'closePopup',
            'showIcon',
            'adblocker',
            'DenyAllCta',
            'AcceptAllCta',
            'highPrivacy',
            'handleBrowserDNTRequest',
            'removeCredit',
            'moreInfoLink',
            'mandatory',
        ];

        $tarteaucitron = $config['tarteaucitron'];
        foreach ($tab as $id) {
            $tarteaucitron[$id] = (bool) $tarteaucitron[$id];
        }

        unset($tarteaucitron['job']);

        $this->environment->AddGlobal('configtarteaucitron', $tarteaucitron);
    }

    protected function setLoginPage(ControllerEvent $controllerEvent): void
    {
        $currentRoute = $controllerEvent->getRequest()->attributes->get('_route');
        $routes = [
            'app_login',
            'admin_profil',
        ];

        if (!in_array($currentRoute, $routes)) {
            return;
        }

        $oauthActivated = $this->dataService->getOauthActivated($this->security->getUser());
        $this->environment->AddGlobal('oauthActivated', $oauthActivated);
    }

    private function setFormatDatetime($config): void
    {
        $this->environment->AddGlobal('formatdatetime', $config['format_datetime']);
    }
}
