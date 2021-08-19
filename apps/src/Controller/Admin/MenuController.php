<?php

namespace Labstag\Controller\Admin;

use Labstag\Entity\Menu;
use Labstag\Form\Admin\Menu\LinkType;
use Labstag\Form\Admin\Menu\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MenuRepository;
use Labstag\RequestHandler\MenuRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{

    protected string $headerTitle = 'Menu';

    protected string $urlHome = 'admin_menu_index';

    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(MenuRepository $repository)
    {
        $all     = $repository->findAllCode();
        $globals = $this->twig->getGlobals();
        $modal   = isset($globals['modal']) ? $globals['modal'] : [];

        $modal['delete'] = true;
        $this->twig->addGlobal('modal', $modal);
        $this->btnInstance->addBtnNew('admin_menu_new');

        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    /**
     * @Route("/add", name="admin_menu_add", methods={"GET"})
     */
    public function add(
        Request $request,
        MenuRequestHandler $requestHandler,
        MenuRepository $repository
    ): Response
    {
        $get = $request->query->all();
        $url = $this->router->generate('admin_menu_index');
        if (isset($get['id'])) {
            return new RedirectResponse($url);
        }

        $parent = $repository->find($get['id']);
        if (!$parent instanceof Menu) {
            return new RedirectResponse($url);
        }

        $menu = new Menu();
        $menu->setParent($parent);

        return $this->create(
            $menu,
            LinkType::class,
            $requestHandler,
            ['list' => 'admin_menu_index'],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/divider/{id}", name="admin_menu_divider")
     */
    public function divider(Menu $menu, MenuRequestHandler $requestHandler): RedirectResponse
    {
        $entity    = new Menu();
        $oldEntity = clone $entity;
        $position  = count($entity->getChildren());
        $entity->setPosition($position + 1);
        $entity->setSeparateur(true);
        $entity->setParent($menu);
        $requestHandler->handle($oldEntity, $entity);

        return new RedirectResponse(
            $this->router->generate('admin_menu_index')
        );
    }

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET"})
     */
    public function new(MenuRequestHandler $requestHandler): Response
    {
        return $this->create(
            new Menu(),
            PrincipalType::class,
            $requestHandler,
            ['list' => 'admin_menu_index'],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/update/{id}", name="admin_menu_update", methods={"GET"})
     */
    public function edit(Menu $menu, MenuRequestHandler $requestHandler)
    {
        $this->modalAttachmentDelete();
        $form = empty($menu->getClef()) ? LinkType::class : PrincipalType::class;

        return $this->update(
            $form,
            $menu,
            $requestHandler,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_menu_index',
            ],
            'admin/menu/form.html.twig'
        );
    }
}
