<?php

namespace Labstag\Service;

use Labstag\Repository\AttachmentRepository;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class FrontService
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

    // @var null|Request
    protected $request;

    public function __construct(
        protected $frontclass,
        protected Environment $environment,
        protected RequestStack $requestStack,
        protected UrlGeneratorInterface $urlGenerator,
        protected AttachmentRepository $attachmentRepository
    )
    {
        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function configMeta($config, $meta)
    {
        $meta = $this->configMetaImage($meta);
        $meta = $this->configMetaRobots($meta);
        $meta = $this->configMetaTitle($config, $meta);
        $meta = $this->configMetaLocale($config, $meta);

        return $this->configMetaDescription($meta);
    }

    public function setBreadcrumb($content)
    {
        $breadcrumb = [];
        foreach ($this->frontclass as $row) {
            $breadcrumb = $row->setBreadcrumb($content, $breadcrumb);
        }

        return array_reverse($breadcrumb);
    }

    public function setMeta($content)
    {
        $meta = [];
        foreach ($this->frontclass as $row) {
            $meta = $row->setMeta($content, $meta);
        }

        foreach ($meta as $key => $value) {
            if (is_null($value)) {
                unset($meta[$key]);
            }
        }

        return $meta;
    }

    public function setMetatags($meta): void
    {
        $metatags = [];
        $meta['twitter:card'] = 'summary_large_image';
        $meta['og:type'] = 'website';

        ksort($meta);
        foreach ($meta as $key => $value) {
            if ('' == $value || is_null($value) || 'title' == $key) {
                continue;
            }

            if (0 != substr_count((string) $key, 'og:')) {
                $metatags[] = [
                    'property' => $key,
                    'content'  => $value,
                ];

                continue;
            }

            $metatags[] = [
                'name'    => $key,
                'content' => $value,
            ];
        }

        $this->environment->AddGlobal('sitemetatags', $metatags);
    }

    private function arrayKeyExists(array $var, $data): bool
    {
        $find = 0;
        foreach ($var as $name) {
            $find = (int) array_key_exists($name, $data);
        }

        return 0 != $find;
    }

    private function configMetaDescription($meta)
    {
        $tests = [
            'og:description',
            'twitter:description',
        ];
        if (!array_key_exists('description', $meta) || $this->arrayKeyExists($tests, $meta)) {
            return $meta;
        }

        $meta['og:description'] = $meta['description'];
        $meta['twitter:description'] = $meta['description'];

        return $meta;
    }

    private function configMetaImage($meta)
    {
        $imageDefault = $this->attachmentRepository->getImageDefault();
        $this->environment->AddGlobal('imageglobal', $imageDefault);
        if (!isset($meta['image']) || is_null($imageDefault) || is_null($imageDefault->getName())) {
            return $meta;
        }

        $image = $meta['image'] ?? $imageDefault->getName();

        $pathPackage = new PathPackage('/', new EmptyVersionStrategy());
        $url = $pathPackage->getUrl($image);
        $meta['og:image'] = $url;
        $meta['twitter:image'] = $url;
        if (isset($meta['image'])) {
            unset($meta['image']);
        }

        return $meta;
    }

    private function configMetaLocale($config, $meta)
    {
        if (!$this->isStateMeta()) {
            return $meta;
        }

        $url = $this->request->getSchemeAndHttpHost();
        $all = $this->request->attributes->all();
        if (isset($all['_route']) && '' != $all['_route']) {
            $url = $this->urlGenerator->generate(
                $all['_route'],
                $all['_route_params'],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }

        $meta['og:locale'] = $config['languagedefault'];
        $meta['og:url'] = $url;
        $meta['twitter:url'] = $url;

        return $meta;
    }

    private function configMetaRobots($meta)
    {
        if (!$this->isStateMeta()) {
            $meta['robots'] = 'noindex';
        }

        return $meta;
    }

    private function configMetaTitle($config, $meta)
    {
        if (!array_key_exists('site_title', $config)) {
            return $meta;
        }

        $sitetitle = $config['site_title'];

        $title = isset($meta['title']) ? $meta['title'].' - '.$sitetitle : $sitetitle;

        if (!isset($meta['title'])) {
            $meta['title'] = $title;
        }

        $meta['og:title'] = $title;
        $meta['twitter:title'] = $title;

        return $meta;
    }

    private function isStateMeta()
    {
        $controller = $this->request->attributes->get('_controller');
        preg_match(self::ADMIN_CONTROLLER, (string) $controller, $matches);

        return 0 == count($matches) || !in_array($controller, self::ERROR_CONTROLLER);
    }
}
