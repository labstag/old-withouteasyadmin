<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\User;
use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\HttpFoundation\Request;
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

    protected function isStateConfig(ControllerEvent $controllerEvent): bool
    {
        $controller = $controllerEvent->getRequest()->attributes->get('_controller');
        $matches    = [];
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

        $globals = $this->twigEnvironment->getGlobals();
        $config  = $globals['config'] ?? $this->dataService->getConfig();
        $this->setConfigMeta($config);
        $this->setConfigTac($config);
        $this->setFormatDatetime($config);
        $this->twigEnvironment->AddGlobal('config', $config);
    }

    protected function setConfigCanonical(): void
    {
        /** @Var Request $request */
        $request   = $this->requestStack->getCurrentRequest();
        $globals   = $this->twigEnvironment->getGlobals();
        $canonical = $globals['canonical'] ?? $request->getUri();
        $this->twigEnvironment->AddGlobal('canonical', $canonical);
    }

    protected function setConfigFavicon(): void
    {
        $attachment = $this->attachmentRepository->getFavicon();
        $this->twigEnvironment->AddGlobal('favicon', $attachment);
    }

    protected function setConfigMeta(array $config): void
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

        $this->twigEnvironment->AddGlobal('configtarteaucitron', $tarteaucitron);
    }

    protected function setLoginPage(ControllerEvent $controllerEvent): void
    {
        $currentRoute = $controllerEvent->getRequest()->attributes->get('_route');
        $routes       = [
            'app_login',
            'admin_profil',
        ];

        if (!in_array($currentRoute, $routes)) {
            return;
        }

        /** @var User $user */
        $user           = $this->security->getUser();
        $oauthActivated = $this->dataService->getOauthActivated($user);
        $this->twigEnvironment->AddGlobal('oauthActivated', $oauthActivated);
    }

    private function setFormatDatetime(array $config): void
    {
        $this->twigEnvironment->AddGlobal('formatdatetime', $config['format_datetime']);
    }
}
