<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\PhoneUserType;
use Labstag\Repository\PhoneUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/user/phone")
 */
class PhoneUserController extends AdminControllerLib
{

    protected string $headerTitle = "Téléphone d'utilisateurs";

    protected string $urlHome = 'admin_phoneuser_index';
    /**
     * @Route("/", name="admin_phoneuser_index", methods={"GET"})
     */
    public function index(PhoneUserRepository $phoneUserRepository): Response
    {
        return $this->adminCrudService->list(
            $phoneUserRepository,
            'findAllForAdmin',
            'admin/phone_user/index.html.twig',
            ['new' => 'admin_phoneuser_new'],
            [
                'list'   => 'admin_phoneuser_index',
                'show'   => 'admin_phoneuser_show',
                'edit'   => 'admin_phoneuser_edit',
                'delete' => 'admin_phoneuser_delete',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_phoneuser_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_phoneuser_new'
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new PhoneUser(),
            PhoneUserType::class,
            ['list' => 'admin_phoneuser_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_phoneuser_show", methods={"GET"})
     */
    public function show(
        PhoneUser $phoneUser,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_phoneuser_show',
                [
                    'id' => $phoneUser->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->read(
            $phoneUser,
            'admin/phone_user/show.html.twig',
            [
                'delete' => 'admin_phoneuser_delete',
                'list'   => 'admin_phoneuser_index',
                'edit'   => 'admin_phoneuser_edit',
            ]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_phoneuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(
        PhoneUser $phoneUser,
        RouterInterface $router
    ): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_phoneuser_edit',
                [
                    'id' => $phoneUser->getId(),
                ]
            ),
        ];
        $this->adminCrudService->addBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            PhoneUserType::class,
            $phoneUser,
            [
                'delete' => 'admin_phoneuser_delete',
                'list'   => 'admin_phoneuser_index',
                'show'   => 'admin_phoneuser_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_phoneuser_delete", methods={"POST"})
     */
    public function delete(PhoneUser $phoneUser): Response
    {
        return $this->adminCrudService->delete($phoneUser);
    }
}
