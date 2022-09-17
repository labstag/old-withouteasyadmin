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

    protected function renderForm(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->setParameters($parameters);

        return parent::renderForm($view, $parameters, $response);
    }

    private function setParameters($parameters)
    {
        $blocksArray = $this->getRepository(Block::class)->getDataByRegion();
        $content     = null;
        if (isset($parameters['content'])) {
            $content = $parameters['content'];
            unset($parameters['content']);
        }

        $parameters['blocks'] = [];
        foreach ($blocksArray as $key => $blocks) {
            $key                        = ('content' == $key) ? 'main' : $key;
            $parameters['blocks'][$key] = [];
            foreach ($blocks as $block) {
                $parameters['blocks'][$key][] = $this->blockService->showContent($block, $content);
            }
        }

        return $parameters;
    }
}
