<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_configuration_index", methods={"GET"})
     */
    public function index(ConfigurationRepository $repository): Response
    {
        return $this->adminCrudService->list(
            $repository,
            'findAllForAdmin',
            'admin/configuration/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_show", methods={"GET"})
     */
    public function show(Configuration $configuration): Response
    {
        return $this->adminCrudService->read(
            $configuration,
            'admin/configuration/show.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_delete", methods={"DELETE"})
     */
    public function delete(Configuration $configuration): Response
    {
        return $this->adminCrudService->delete(
            $configuration,
            'admin_configuration_index'
        );
    }
}
