<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Attachment;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\PostRepository;
use Labstag\Service\DataService;
use Labstag\Service\HistoryService;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class TemplatePageLib
{

    protected BookmarkRepository $bookmarkRepository;

    protected CategoryRepository $categoryRepository;

    protected ContainerBagInterface $containerBag;

    protected DataService $dataService;

    protected EditoRepository $editoRepository;

    protected HistoryRepository $historyRepository;

    protected HistoryService $historyService;

    protected LibelleRepository $libelleRepository;

    protected PaginatorInterface $paginator;

    protected PostRepository $postRepository;

    protected Request $request;

    protected Environment $twig;

    public function __construct(
        Environment $twig,
        ContainerBagInterface $containerBag,
        HistoryService $historyService,
        DataService $dataService,
        HistoryRepository $historyRepository,
        BookmarkRepository $bookmarkRepository,
        EditoRepository $editoRepository,
        PostRepository $postRepository,
        LibelleRepository $libelleRepository,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository
    )
    {
        $this->containerBag       = $containerBag;
        $this->historyRepository  = $historyRepository;
        $this->historyService     = $historyService;
        $this->bookmarkRepository = $bookmarkRepository;
        $this->dataService        = $dataService;
        $this->paginator          = $paginator;
        $this->editoRepository    = $editoRepository;
        $this->postRepository     = $postRepository;
        $this->libelleRepository  = $libelleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->twig               = $twig;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $content = $this->twig->render($view, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    protected function setMetaOpenGraph(
        string $title,
        string $keywords,
        string $description,
        $image
    )
    {
        $globals = $this->twig->getGlobals();
        $config  = $globals['config'] ?? $this->dataService->getConfig();

        $config['meta'] = !array_key_exists('meta', $config) ? [] : $config['meta'];

        $meta = $config['meta'];

        if ('' != $keywords) {
            $meta['keywords'] = $keywords;
        }

        $this->setMetaOpenGraphDescription($description, $meta);
        $this->setMetaOpenGraphTitle($title, $config, $meta);
        $this->setMetaOpenGraphImage($image, $meta);
        $config['meta'] = $meta;
        ksort($config['meta']);

        $this->twig->AddGlobal('config', $config);
        $this->setMetatags($config['meta']);
    }

    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    private function setMetaOpenGraphDescription($description, &$meta)
    {
        if ('' == $description) {
            return;
        }

        $meta['description']         = $description;
        $meta['og:description']      = $description;
        $meta['twitter:description'] = $description;
    }

    private function setMetaOpenGraphImage(
        $image,
        &$meta
    )
    {
        if (is_null($image) || !$image instanceof Attachment) {
            return;
        }

        $package               = new PathPackage('/', new EmptyVersionStrategy());
        $url                   = $package->getUrl($image->getName());
        $meta['image']         = $url;
        $meta['og:image']      = $url;
        $meta['twitter:image'] = $url;
    }

    private function setMetaOpenGraphTitle($title, $config, &$meta)
    {
        if ('' == $title) {
            return;
        }

        if (array_key_exists('site_title', $config) && array_key_exists('title_format', $config)) {
            $title = str_replace(
                [
                    '%titlesite%',
                    '%titlepost%',
                ],
                [
                    $config['site_title'],
                    $title,
                ],
                $config['title_format']
            );
        }

        $meta['title']         = $title;
        $meta['og:title']      = $title;
        $meta['twitter:title'] = $title;
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
}
