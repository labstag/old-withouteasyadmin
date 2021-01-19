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
use Labstag\Annotation\IgnoreSoftDelete;

/**
 * @Route("/admin/configuration")
 */
class ConfigurationController extends AdminControllerLib
{

    protected string $headerTitle = 'Configuration';

    protected string $urlHome = 'admin_configuration_index';

    /**
     * @Route("/trash", name="admin_configuration_trash", methods={"GET"})
     * @Route("/", name="admin_configuration_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(ConfigurationRepository $repository): Response
    {
        return $this->adminCrudService->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/configuration/index.html.twig',
            [
                'empty' => 'admin_configuration_empty',
                'trash' => 'admin_configuration_trash',
                'list'  => 'admin_configuration_index',
            ],
            [
                'list'    => 'admin_configuration_index',
                'show'    => 'admin_configuration_show',
                'preview' => 'admin_configuration_preview',
                'delete'  => 'admin_configuration_delete',
                'destroy' => 'admin_configuration_destroy',
                'restore' => 'admin_configuration_restore',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_configuration_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Configuration $configuration): Response
    {
        return $this->adminCrudService->showOrPreview(
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
     * @Route("/destroy/{id}", name="admin_configuration_destroy", methods={"DELETE"})
     * @Route("/restore/{id}", name="admin_configuration_restore")
     * @IgnoreSoftDelete
     */
    public function entityDeleteDestroyRestore(Configuration $configuration): Response
    {
        return $this->adminCrudService->entityDeleteDestroyRestore($configuration);
    }

    /**
     * @IgnoreSoftDelete
     * @Route("/empty", name="admin_configuration_empty", methods={"DELETE"})
     */
    public function empty(ConfigurationRepository $repository): Response
    {
        return $this->adminCrudService->empty($repository);
    }
}
