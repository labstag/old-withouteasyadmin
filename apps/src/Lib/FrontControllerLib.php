<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\DataService;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Twig\Environment;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class FrontControllerLib extends ControllerLib
{

    protected Environment $twig;

    public function __construct(
        DataService $dataService,
        Breadcrumbs $breadcrumbs,
        Environment $twig,
        PaginatorInterface $paginator
    )
    {
        $this->twig = $twig;
        parent::__construct($dataService, $breadcrumbs, $paginator);
    }

    protected function setMetaOpenGraph(
        string $title,
        string $keywords,
        string $description,
        $image
    )
    {
        $globals = $this->twig->getGlobals();
        $config  = isset($globals['config']) ? $globals['config'] : $this->dataService->getConfig();

        $config['meta'] = !array_key_exists('meta', $config) ? [] : $config['meta'];

        $meta = $config['meta'];

        if ('' != $keywords) {
            $meta['keywords'] = $keywords;
        }

        if ('' != $description) {
            $meta['description']         = $description;
            $meta['og:description']      = $description;
            $meta['twitter:description'] = $description;
        }

        if ('' != $title) {
            if (array_key_exists('site_title', $config)) {
                $title = sprintf(
                    '%s - %s',
                    $config['site_title'],
                    $title
                );
            }

            $meta['title']         = $title;
            $meta['og:title']      = $title;
            $meta['twitter:title'] = $title;
        }

        if (!is_null($image)) {
            $package               = new PathPackage('/', new EmptyVersionStrategy());
            $url                   = $package->getUrl($image->getName());
            $meta['image']         = $url;
            $meta['og:image']      = $url;
            $meta['twitter:image'] = $url;
        }

        $config['meta'] = $meta;

        ksort($config['meta']);

        $this->twig->addGlobal('config', $config);
        $this->setMetatags($config['meta']);
    }

    private function setMetatags($meta)
    {
        $metatags = [];
        foreach ($meta as $key => $value) {
            if ('' == $value) {
                continue;
            }

            if (0 != substr_count($key, 'og:')) {
                $metatags[] = [
                    'property' => $key,
                    'content'  => $value,
                ];
                continue;
            } elseif ('description' == $key) {
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

        $this->twig->addGlobal('sitemetatags', $metatags);
    }
}
