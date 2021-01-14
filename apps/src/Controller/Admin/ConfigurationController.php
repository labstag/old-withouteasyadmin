<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationController extends AdminControllerLib
{

    protected string $headerTitle = 'Configuration';

    protected string $urlHome = 'admin_configuration_index';
    /**
     * @Route("/", name="admin_configuration_index", methods={"GET"})
     */
    public function index(ConfigurationRepository $repository): Response
    {
        return $this->adminCrudService->list(
            $repository,
            'findAllForAdmin',
            'admin/configuration/index.html.twig',
            [],
            [
                'list'   => 'admin_configuration_index',
                'show'   => 'admin_configuration_show',
                'delete' => 'admin_configuration_delete',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_show", methods={"GET"})
     */
    public function show(
        Configuration $configuration,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_configuration_show',
                [
                    'id' => $configuration->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->read(
            $configuration,
            'admin/configuration/show.html.twig',
            ['list' => 'admin_configuration_index']
        );
    }

    /**
     * @Route(
     *  "/delete/{id}",
     *  name="admin_configuration_delete",
     *  methods={"POST"}
     * )
     */
    public function delete(Configuration $configuration): Response
    {
        return $this->adminCrudService->delete($configuration);
    }
}
