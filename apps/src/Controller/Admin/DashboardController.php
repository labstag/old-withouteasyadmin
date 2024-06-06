<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Labstag\Entity\Groupe;
use Labstag\Entity\HttpErrorLogs;
use Labstag\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    )
    {

    }

    #[Route('/admin/cache-clear', name: 'admin_cache_clear')]
    public function cacheclear(KernelInterface $kernel): Response
    {
        //execution de la commande en console
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $arrayInput = new ArrayInput(['cache:clear']);

        $bufferedOutput = new BufferedOutput();
        $application->run($arrayInput, $bufferedOutput);

        $this->addFlash('success', 'Cache vidé');

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render(
            'admin/layout.html.twig',
            [

            ]
        );
    }

    public function configureDashboard(): Dashboard
    {
        $dashboard = Dashboard::new();
        $dashboard->setTitle('Www');

        return $dashboard;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Utilisateurs');
        $other = [
            [
                'Utilisateurs',
                'fas fa-users',
                User::class,
            ],
            [
                'Groupes',
                'fas fa-users-cog',
                Groupe::class,
            ],
        ];

        foreach ($other as $row) {
            $repository = $this->entityManager->getRepository($row[2]);
            $count      = count($repository->findAll());
            yield MenuItem::LinkToCrud(
                $row[0].' ('.$count.')',
                $row[1],
                $row[2]
            );
        }
        yield MenuItem::section();
        $other = [
            [
                'Erreurs',
                'fas fa-bug',
                HttpErrorLogs::class,
            ],
        ];

        foreach ($other as $row) {
            $repository = $this->entityManager->getRepository($row[2]);
            $count      = count($repository->findAll());
            yield MenuItem::LinkToCrud(
                $row[0].' ('.$count.')',
                $row[1],
                $row[2]
            );
        }
        yield MenuItem::section();
        yield MenuItem::linkToRoute('Vider le cache', 'fas fa-trash', 'admin_cache_clear');
    }
}
