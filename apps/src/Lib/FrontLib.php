<?php

namespace Labstag\Lib;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Repository\PageRepository;
use Labstag\Service\RepositoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class FrontLib
{

    protected Request $request;

    public function __construct(
        protected RepositoryService $repositoryService,
        protected EntityManagerInterface $entityManager,
        protected PageRepository $pageRepository,
        protected RequestStack $requestStack,
        protected RouterInterface $router
    )
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function getMeta(array|Collection $metas, array $meta): array
    {
        foreach ($metas as $entity) {
            $meta['description'] = $entity->getDescription();
            $meta['keywords']    = $entity->getKeywords();
            $meta['title']       = $entity->getTitle();
        }

        return $meta;
    }
}
