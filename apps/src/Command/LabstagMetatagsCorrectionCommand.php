<?php

namespace Labstag\Command;

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
    public function executeChapter(): void
    {
        /** @var ChapterRepository $repository */
        $repository = $this->repositoryService->get(Chapter::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var Chapter $entity */
            $repository->save($entity);
        }
    }

    public function executeEdito(): void
    {
        /** @var EditoRepository $repository */
        $repository = $this->repositoryService->get(Edito::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var Edito $entity */
            $repository->save($entity);
        }
    }

    public function executeHistory(): void
    {
        /** @var HistoryRepository $repository */
        $repository = $this->repositoryService->get(History::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var History $entity */
            $repository->save($entity);
        }
    }

    public function executePage(): void
    {
        /** @var PageRepository $repository */
        $repository = $this->repositoryService->get(Page::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var Page $entity */
            $repository->save($entity);
        }
    }

    public function executePost(): void
    {
        /** @var PostRepository $repository */
        $repository = $this->repositoryService->get(Post::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var Post $entity */
            $repository->save($entity);
        }
    }

    public function executeRender(): void
    {
        /** @var RenderRepository $repository */
        $repository = $this->repositoryService->get(Render::class);
        $all        = $repository->findAll();
        foreach ($all as $entity) {
            /** @var Render $entity */
            $repository->save($entity);
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
