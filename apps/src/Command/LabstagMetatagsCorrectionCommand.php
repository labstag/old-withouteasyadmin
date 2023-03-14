<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Entity\Render;
use Labstag\Lib\CommandLib;
use Labstag\Repository\ChapterRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\RenderRepository;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\RequestHandler\RenderRequestHandler;
use Labstag\Service\RepositoryService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'labstag:metatags:correction'
)]
class LabstagMetatagsCorrectionCommand extends CommandLib
{
    public function __construct(
        RepositoryService $repositoryService,
        EntityManagerInterface $entityManager,
        protected ChapterRepository $chapterRepository,
        protected EditoRepository $editoRepository,
        protected HistoryRepository $historyRepository,
        protected PageRepository $pageRepository,
        protected PostRepository $postRepository,
        protected RenderRepository $renderRepository,
        protected ChapterRequestHandler $chapterRequestHandler,
        protected EditoRequestHandler $editoRequestHandler,
        protected HistoryRequestHandler $historyRequestHandler,
        protected PageRequestHandler $pageRequestHandler,
        protected PostRequestHandler $postRequestHandler,
        protected RenderRequestHandler $renderRequestHandler
    )
    {
        parent::__construct($repositoryService, $entityManager);
    }

    public function executeChapter(): void
    {
        $all = $this->chapterRepository->findAll();
        foreach ($all as $entity) {
            /** @var Chapter $entity */
            $old = clone $entity;
            $this->chapterRequestHandler->handle($old, $entity);
        }
    }

    public function executeEdito(): void
    {
        $all = $this->editoRepository->findAll();
        foreach ($all as $entity) {
            /** @var Edito $entity */
            $old = clone $entity;
            $this->editoRequestHandler->handle($old, $entity);
        }
    }

    public function executeHistory(): void
    {
        $all = $this->historyRepository->findAll();
        foreach ($all as $entity) {
            /** @var History $entity */
            $old = clone $entity;
            $this->historyRequestHandler->handle($old, $entity);
        }
    }

    public function executePage(): void
    {
        $all = $this->pageRepository->findAll();
        foreach ($all as $entity) {
            /** @var Page $entity */
            $old = clone $entity;
            $this->pageRequestHandler->handle($old, $entity);
        }
    }

    public function executePost(): void
    {
        $all = $this->postRepository->findAll();
        foreach ($all as $entity) {
            /** @var Post $entity */
            $old = clone $entity;
            $this->postRequestHandler->handle($old, $entity);
        }
    }

    public function executeRender(): void
    {
        $all = $this->renderRepository->findAll();
        foreach ($all as $entity) {
            /** @var Render $entity */
            $old = clone $entity;
            $this->renderRequestHandler->handle($old, $entity);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $functions    = [
            'executeChapter',
            'executeEdito',
            'executeHistory',
            'executePage',
            'executePost',
            'executeRender',
        ];
        foreach ($functions as $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            call_user_func($callable);
        }

        $symfonyStyle->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
