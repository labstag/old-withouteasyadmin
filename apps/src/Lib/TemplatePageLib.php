<?php

namespace Labstag\Lib;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Attachment;
use Labstag\Entity\Page;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\UserRepository;
use Labstag\Service\DataService;
use Labstag\Service\HistoryService;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Throwable;
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

    protected RequestStack $requestStack;

    protected RouterInterface $router;

    protected Environment $twig;

    protected UserRepository $userRepository;

    public function __construct(
        RequestStack $requestStack,
        RouterInterface $router,
        Environment $twig,
        ContainerBagInterface $containerBag,
        HistoryService $historyService,
        DataService $dataService,
        UserRepository $userRepository,
        HistoryRepository $historyRepository,
        BookmarkRepository $bookmarkRepository,
        EditoRepository $editoRepository,
        PostRepository $postRepository,
        LibelleRepository $libelleRepository,
        PaginatorInterface $paginator,
        CategoryRepository $categoryRepository
    )
    {
        $this->router             = $router;
        $this->userRepository     = $userRepository;
        $this->requestStack       = $requestStack;
        $request                  = $this->requestStack->getCurrentRequest();
        $this->request            = $request;
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

    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        unset($page, $route, $params, $relative);

        return '';
    }

    public function getId(): string
    {
        return '';
    }

    public function launch($matches)
    {
        unset($matches);
    }

    protected function createNotFoundException(string $message = 'Not Found', ?Throwable $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    protected function getCaseRegex(): array
    {
        return [];
    }

    protected function getCaseSlug($slug)
    {
        $regex  = $this->getCaseRegex();
        $case   = '';
        $search = [];
        foreach ($regex as $key => $value) {
            preg_match($key, $slug, $matches);
            if (0 != count($matches)) {
                $search = $matches;
                $case   = $value;

                break;
            }
        }

        return [
            $case,
            $search,
        ];
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $content = $this->twig->render($view, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
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
