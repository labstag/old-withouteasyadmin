<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Repository\ChapterRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\RenderRepository;
use Labstag\Repository\UserRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Service\GeocodeService;
use Labstag\Service\GuardService;
use Labstag\Service\HistoryService;
use Labstag\Service\InstallService;
use Labstag\Service\RepositoryService;
use Labstag\Service\WorkflowService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class CommandLib extends Command
{
    public function __construct(
        protected mixed $serverenv,
        protected RewindableGenerator $rewindableGenerator,
        protected EventDispatcherInterface $eventDispatcher,
        protected WorkflowRepository $workflowRepository,
        protected WorkflowService $workflowService,
        protected GroupeRepository $groupeRepository,
        protected UserRepository $userRepository,
        protected ChapterRepository $chapterRepository,
        protected EditoRepository $editoRepository,
        protected PageRepository $pageRepository,
        protected PostRepository $postRepository,
        protected RenderRepository $renderRepository,
        protected InstallService $installService,
        protected GeocodeService $geocodeService,
        protected ParameterBagInterface $parameterBag,
        protected HistoryService $historyService,
        protected HistoryRepository $historyRepository,
        protected GuardService $guardService,
        protected RepositoryService $repositoryService,
        protected EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }
}
