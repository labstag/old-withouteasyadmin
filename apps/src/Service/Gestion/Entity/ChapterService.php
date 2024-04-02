<?php

namespace Labstag\Service\Gestion\Entity;

use Exception;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\ChapterRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class ChapterService extends ViewService implements AdminEntityServiceInterface
{
    public function add(
        History $history
    ): RedirectResponse
    {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['edit'])) {
            throw new Exception('Route not found');
        }

        $chapter = new Chapter();
        $chapter->setHistory($history);
        $chapter->setName(Uuid::v1());
        $chapter->setPosition((is_countable($history->getChapters()) ? count($history->getChapters()) : 0) + 1);

        /** @var ChapterRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Chapter::class);
        $repositoryLib->save($chapter);

        return $this->redirectToRoute($routes['edit'], ['id' => $chapter->getId()]);
    }

    public function getType(): string
    {
        return Chapter::class;
    }
}
