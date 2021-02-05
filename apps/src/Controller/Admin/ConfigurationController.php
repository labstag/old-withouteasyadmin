<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Configuration;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/configuration/index.html.twig',
            [
                'empty' => 'api_action_empty',
                'trash' => 'admin_configuration_trash',
                'list'  => 'admin_configuration_index',
            ],
            [
                'list'    => 'admin_configuration_index',
                'show'    => 'admin_configuration_show',
                'preview' => 'admin_configuration_preview',
                'delete'  => 'api_action_delete',
                'destroy' => 'api_action_destroy',
                'restore' => 'api_action_restore',
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
        return $this->renderShowOrPreview(
            $configuration,
            'admin/configuration/show.html.twig',
            ['list' => 'admin_configuration_index']
        );
    }
}
