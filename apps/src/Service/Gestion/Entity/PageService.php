<?php

namespace Labstag\Service\Gestion\Entity;

use Exception;
use Labstag\Entity\Page;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\PageRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class PageService extends ViewService implements AdminEntityServiceInterface
{
    public function add(): RedirectResponse
    {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['edit'])) {
            throw new Exception('Route edit not found');
        }

        $page = new Page();
        $page->setName(Uuid::v1());

        /** @var PageRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Page::class);
        $repositoryLib->save($page);

        return $this->redirectToRoute($routes['edit'], ['id' => $page->getId()]);
    }

    public function getType(): string
    {
        return Page::class;
    }
}
