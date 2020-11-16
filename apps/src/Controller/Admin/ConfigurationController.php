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
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        ConfigurationRepository $repository
    ): Response
    {
        $pagination = $paginator->paginate(
            $repository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/configuration/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_show", methods={"GET"})
     */
    public function show(Configuration $configuration): Response
    {
        return $this->render(
            'admin/configuration/show.html.twig',
            ['configuration' => $configuration]
        );
    }

    /**
     * @Route("/{id}", name="admin_configuration_delete", methods={"DELETE"})
     */
    public function delete(
        Request $request,
        Configuration $configuration
    ): Response
    {
        $this->deleteEntity($request, $configuration);

        return $this->redirectToRoute('admin_configuration_index');
    }
}
