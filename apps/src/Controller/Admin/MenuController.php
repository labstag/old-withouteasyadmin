<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\MenuType;
use Labstag\Repository\MenuRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        MenuRepository $menuRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $menuRepository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/menu/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $menu   = new Menu();
        $form   = $this->createForm(MenuType::class, $menu);
        $return = $this->newForm($request, $form, $menu);
        if ($return) {
            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render(
            'admin/menu/new.html.twig',
            [
                'menu' => $menu,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_menu_show", methods={"GET"})
     */
    public function show(Menu $menu): Response
    {
        return $this->render(
            'admin/menu/show.html.twig',
            ['menu' => $menu]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Menu $menu): Response
    {
        $form   = $this->createForm(MenuType::class, $menu);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_menu_index');
        }

        return $this->render(
            'admin/menu/edit.html.twig',
            [
                'menu' => $menu,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Menu $menu): Response
    {
        $this->deleteEntity($request, $menu);

        return $this->redirectToRoute('admin_menu_index');
    }
}
