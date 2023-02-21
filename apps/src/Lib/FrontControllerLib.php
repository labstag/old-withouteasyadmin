<?php

namespace Labstag\Lib;

use Labstag\Entity\Block;
use Labstag\Entity\Page;
use Labstag\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Response;

abstract class FrontControllerLib extends ControllerLib
{
    public function page(
        string $slug,
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
            'front.html.twig',
            ['content' => $page]
        );
    }

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->setParameters($parameters);

        return parent::render($view, $parameters, $response);
    }

    private function setParameters($parameters)
    {
        $blocksArray = $this->getRepository(Block::class)->getDataByRegion();
        $content = null;
        if (isset($parameters['content'])) {
            $content = $parameters['content'];
            unset($parameters['content']);
        }

        $globals = $this->environment->getGlobals();

        $config = $globals['config'] ?? $this->dataService->getConfig();
        $tagsmeta = $config['meta'] ?? [];
        $tagsmeta = array_merge(
            $tagsmeta,
            $this->frontService->setMeta($content)
        );

        $config['meta'] = $this->frontService->configMeta($config, $tagsmeta);

        $this->frontService->setMetatags($config['meta']);
        $this->environment->AddGlobal('config', $config);

        $parameters['blocks'] = [];
        foreach ($blocksArray as $key => $blocks) {
            $key = ('content' == $key) ? 'main' : $key;
            $parameters['blocks'][$key] = [];
            foreach ($blocks as $block) {
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
