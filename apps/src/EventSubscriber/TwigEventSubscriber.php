<?php

namespace Labstag\EventSubscriber;

use Labstag\Repository\AttachmentRepository;
use Labstag\Service\DataService;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    final public const ADMIN_CONTROLLER = '/(Controller\\\Admin)/';

    final public const ERROR_CONTROLLER = [
        'error_controller',
        'error_controller::preview',
    ];

    final public const LABSTAG_CONTROLLER = '/(Labstag)/';

    public function __construct(
        protected RouterInterface $router,
        protected Environment $twig,
        protected UrlGeneratorInterface $urlGenerator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected DataService $dataService,
        protected Security $security,
        protected TranslatorInterface $translator,
        protected AttachmentRepository $attachmentRepo
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [ControllerEvent::class => 'onControllerEvent'];
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $this->setLoginPage($event);
        $this->setConfig($event, $request);
    }

    protected function setConfig(ControllerEvent $event, Request $request): void
    {
        $favicon    = $this->attachmentRepo->getFavicon();
        $controller = $event->getRequest()->attributes->get('_controller');
        $matches    = [];
        preg_match(self::LABSTAG_CONTROLLER, (string) $controller, $matches);
        if (0 == count($matches) && !in_array($controller, self::ERROR_CONTROLLER)) {
            return;
        }

        $globals   = $this->twig->getGlobals();
        $canonical = $globals['canonical'] ?? $request->getUri();

        $config = $globals['config'] ?? $this->dataService->getConfig();

        $config['meta'] = array_key_exists('meta', $config) ? $config['meta'] : [];
        $this->setMetaTitleGlobal($config);
        preg_match(self::ADMIN_CONTROLLER, (string) $controller, $matches);
        $state = (0 == count($matches) || !in_array($controller, self::ERROR_CONTROLLER));
        $this->setConfigGlobal($state, $config, $request);
        if (!$state) {
            $config['meta']['robots'] = 'noindex';
        }

        ksort($config['meta']);

        $this->setMetatags($config['meta']);
        $this->setConfigTac($config);
        $this->setFormatDatetime($config);

        $this->twig->AddGlobal('config', $config);
        $this->twig->AddGlobal('favicon', $favicon);
        $this->twig->AddGlobal('canonical', $canonical);
    }

    protected function setConfigTac(array $config)
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

        $this->twig->AddGlobal('configtarteaucitron', $tarteaucitron);
    }

    protected function setLoginPage(ControllerEvent $event): void
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');
        $routes       = [
            'app_login',
            'admin_profil',
        ];

        if (!in_array($currentRoute, $routes)) {
            return;
        }

        $oauthActivated = $this->dataService->getOauthActivated($this->security->getUser());
        $this->twig->AddGlobal('oauthActivated', $oauthActivated);
    }

    private function arrayKeyExists(array $var, $data)
    {
        $find = 0;
        foreach ($var as $name) {
            $find = (int) array_key_exists($name, $data);
        }

        return 0 != $find;
    }

    private function setConfigGlobal(bool $enable, array &$config, Request $request)
    {
        if (!$enable) {
            return;
        }

        $this->setMetaTitle($config);
        $this->setMetaImage($config);
        $this->setMetaDescription($config);
        $url = $request->getSchemeAndHttpHost();
        $all = $request->attributes->all();
        if (isset($all['_route']) && '' != $all['_route']) {
            $url = $this->urlGenerator->generate(
                $all['_route'],
                $all['_route_params'],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $config['meta']['og:locale']    = $config['languagedefault'];
        $config['meta']['og:url']       = $url;
        $config['meta']['twitter:url']  = $url;
        $config['meta']['og:type']      = 'website';
        $config['meta']['twitter:card'] = 'summary_large_image';
    }

    private function setFormatDatetime($config)
    {
        $this->twig->AddGlobal('formatdatetime', $config['format_datetime']);
    }

    private function setMetaDescription(&$config)
    {
        $meta  = $config['meta'];
        $tests = [
            'og:description',
            'twitter:description',
        ];
        if (!array_key_exists('description', $meta) || $this->arrayKeyExists($tests, $meta)) {
            return;
        }

        $meta['og:description']      = $meta['description'];
        $meta['twitter:description'] = $meta['description'];

        $config['meta'] = $meta;
    }

    private function setMetaImage(&$config)
    {
        $image = $this->attachmentRepo->getImageDefault();
        $this->twig->AddGlobal('imageglobal', $image);
        $meta  = $config['meta'];
        $tests = [
            'og:image',
            'twitter:image',
        ];
        if ($this->arrayKeyExists($tests, $meta)) {
            return;
        }

        if (is_null($image) || is_null($image->getName())) {
            return;
        }

        $package = new PathPackage('/', new EmptyVersionStrategy());
        $url     = $package->getUrl($image->getName());

        $meta['og:image']      = $url;
        $meta['twitter:image'] = $url;

        $config['meta'] = $meta;
    }

    private function setMetatags($meta)
    {
        $metatags = [];
        foreach ($meta as $key => $value) {
            if ('' == $value) {
                continue;
            }

            if (0 != substr_count((string) $key, 'og:')) {
                $metatags[] = [
                    'property' => $key,
                    'content'  => $value,
                ];

                continue;
            }

            if ('description' == $key) {
                $metatags[] = [
                    'itemprop' => $key,
                    'content'  => $value,
                ];
                $metatags[] = [
                    'name'    => $key,
                    'content' => $value,
                ];

                continue;
            }

            $metatags[] = [
                'name'    => $key,
                'content' => $value,
            ];
        }

        $this->twig->AddGlobal('sitemetatags', $metatags);
    }

    private function setMetaTitle(&$config)
    {
        if (!array_key_exists('site_title', $config)) {
            return;
        }

        $meta = $config['meta'];
        if (array_key_exists('og:title', $meta) || array_key_exists('twitter:title', $meta)) {
            return;
        }

        $meta['og:title']      = $config['site_title'];
        $meta['twitter:title'] = $config['site_title'];

        $config['meta'] = $meta;
    }

    private function setMetaTitleGlobal(&$config)
    {
        $meta = $config['meta'];
        if (!array_key_exists('site_title', $config) && array_key_exists('title', $meta)) {
            return;
        }

        $config['meta']['title'] = $config['site_title'];
    }
}
