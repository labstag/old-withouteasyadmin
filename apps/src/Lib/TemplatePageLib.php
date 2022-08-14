<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Attachment;
use Labstag\Entity\Page;
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

    protected Request $request;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected RequestStack $requeststack,
        protected RouterInterface $router,
        protected Environment $twig,
        protected ContainerBagInterface $containerBag,
        protected HistoryService $historyService,
        protected DataService $dataService,
        protected PaginatorInterface $paginator,
    )
    {
        $request       = $this->requeststack->getCurrentRequest();
        $this->request = $request;
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
            preg_match($key, (string) $slug, $matches);
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

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
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
        $metas,
        $image
    )
    {
        $keywords = (count($metas) != 0) ? $metas[0]->getKeywords() : '';
        $description = (count($metas) != 0) ? $metas->getDescription() : '';
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
