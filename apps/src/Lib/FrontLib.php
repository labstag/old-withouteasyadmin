<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Labstag\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class FrontLib
{

    protected Request $request;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected PageRepository $pageRepository,
        protected RequestStack $requestStack,
        protected RouterInterface $router
    )
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function getMeta($metas, $meta)
    {
        foreach ($metas as $entity) {
            $meta['description'] = $entity->getDescription();
            $meta['keywords']    = $entity->getKeywords();
            $meta['title']       = $entity->getTitle();
        }

        return $meta;
    }

    protected function getRepository(string $entity): EntityRepository
    {
        return $this->entityManager->getRepository($entity);
    }
}