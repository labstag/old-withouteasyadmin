<?php

namespace Labstag\Lib;

use Labstag\Entity\Block;
use Labstag\Entity\Page;
use Labstag\Repository\BlockRepository;
use Labstag\Repository\PageRepository;
use Labstag\Service\BlockService;
use Labstag\Service\DataService;
use Labstag\Service\FrontService;
use Labstag\Service\RepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class FrontControllerLib extends AbstractController
{
    public function __construct(
        protected FrontService $frontService,
        protected Environment $twigEnvironment,
        protected RepositoryService $repositoryService,
        protected DataService $dataService,
        protected BlockService $blockService
    )
    {
    }

    public function page(
        ?string $slug,
        PageRepository $pageRepository
    ): Response
    {
        $page = $pageRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$page instanceof Page) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'skeleton/front.html.twig',
            ['content' => $page]
        );
    }

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $redirect   = null;
        $parameters = $this->setParameters($parameters);
        if (isset($parameters['blocks'])) {
            $redirect = $this->getRedirection($parameters['blocks']);
        }

        if (!is_null($redirect)) {
            return $redirect;
        }

        return parent::render($view, $parameters, $response);
    }

    private function getRedirection(array $blocks): ?RedirectResponse
    {
        $redirect = null;

        foreach ($blocks as $block) {
            foreach ($block as $row) {
                if (!$row['data'] instanceof RedirectResponse) {
                    continue;
                }

                $redirect = $row['data'];

                break;
            }
        }

        return $redirect;
    }

    private function setParameters(array $parameters): array
    {
        /** @var BlockRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Block::class);
        $content       = null;
        if (isset($parameters['content'])) {
            $content = $parameters['content'];
            unset($parameters['content']);
        }

        $blocksArray = $repositoryLib->getDataByRegion($content);
        $globals     = $this->twigEnvironment->getGlobals();
        $config      = $globals['config'] ?? $this->dataService->getConfig();
        if (!is_array($config)) {
            $config = [];
        }

        $tagsmeta = $config['meta'] ?? [];
        $tagsmeta = array_merge(
            $tagsmeta,
            $this->frontService->setMeta($content)
        );

        $config['meta'] = $this->frontService->configMeta($config, $tagsmeta);
        if (!is_array($config['meta'])) {
            $config['meta'] = [];
        }

        $this->frontService->setMetatags($config['meta']);
        $this->twigEnvironment->AddGlobal('config', $config);

        $parameters['blocks'] = [];
        foreach ($blocksArray as $key => $blocks) {
            $key                        = ('content' == $key) ? 'main' : $key;
            $parameters['blocks'][$key] = [];
            foreach ($blocks as $block) {
                /** @var Block $block */
                $data = $this->blockService->showContent($block, $content);
                if (is_null($data)) {
                    continue;
                }

                $template = $this->blockService->showTemplate($block, $content);

                $parameters['blocks'][$key][] = [
                    'template' => $template,
                    'data'     => $data,
                ];
            }
        }

        return $parameters;
    }
}
