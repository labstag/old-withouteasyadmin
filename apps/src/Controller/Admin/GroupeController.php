<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\GroupeType;
use Labstag\Repository\GroupeRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/user/groupe")
 */
class GroupeController extends AdminControllerLib
{

    protected string $headerTitle = "Groupe d'utilisateurs";

    protected string $urlHome = 'admin_groupuser_index';
    /**
     * @Route("/", name="admin_groupuser_index", methods={"GET"})
     */
    public function index(GroupeRepository $groupeRepository): Response
    {
        return $this->adminCrudService->list(
            $groupeRepository,
            'findAllForAdmin',
            'admin/groupe/index.html.twig',
            ['new' => 'admin_groupuser_new'],
            [
                'list'   => 'admin_groupuser_index',
                'show'   => 'admin_groupuser_show',
                'edit'   => 'admin_groupuser_edit',
                'delete' => 'admin_groupuser_delete',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_groupuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_groupuser_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new Groupe(),
            GroupeType::class,
            ['list' => 'admin_groupuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_groupuser_show", methods={"GET"})
     */
    public function show(Groupe $groupe, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_groupuser_show',
                [
                    'id' => $groupe->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->read(
            $groupe,
            'admin/groupe/show.html.twig',
            [
                'delete' => 'admin_groupuser_delete',
                'edit'   => 'admin_groupuser_edit',
                'list'   => 'admin_groupuser_index',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_groupuser_edit", methods={"GET","POST"})
     */
    public function edit(Groupe $groupe, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_groupuser_edit',
                [
                    'id' => $groupe->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            GroupeType::class,
            $groupe,
            [
                'delete' => 'admin_groupuser_delete',
                'list'   => 'admin_groupuser_index',
                'show'   => 'admin_groupuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_groupuser_delete", methods={"DELETE"})
     */
    public function delete(Groupe $groupe): Response
    {
        return $this->adminCrudService->delete($groupe);
    }
}
