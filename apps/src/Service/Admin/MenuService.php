<?php

namespace Labstag\Service\Admin;

use Exception;
use Labstag\Entity\Menu;
use Labstag\Repository\MenuRepository;
use Labstag\Service\AdminService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuService extends AdminService
{
    public function add(array $parameters = []): Response
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $routes  = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['list'])) {
            throw new Exception('Route list not found');
        }

        $get = $request->query->all();
        $url = $this->generateUrl($routes['list']);
        if (!isset($get['id'])) {
            return new RedirectResponse($url);
        }

        /** @var MenuRepository $menuRepository */
        $menuRepository = $this->repositoryService->get(Menu::class);
        $parent         = $menuRepository->find($get['id']);
        if (!$parent instanceof Menu) {
            return new RedirectResponse($url);
        }

        $menu = new Menu();
        $data = [$menu->getData()];
        $menu->setClef(null);
        $menu->setData($data);
        $menu->setSeparateur(false);

        $children = $parent->getChildren();
        $position = is_countable($children) ? count($children) : 0;
        $menu->setPosition($position + 1);
        $menu->setParent($parent);

        return $this->editOrNew('edit', $menu, $parameters);
    }

    public function divider(Menu $menu)
    {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['list'])) {
            throw new Exception('Route list not found');
        }

        $entity   = new Menu();
        $children = $menu->getChildren();
        $position = is_countable($children) ? count($children) : 0;
        $entity->setPosition($position + 1);
        $entity->setSeparateur(true);
        $entity->setParent($menu);

        /** @var MenuRepository $menuRepository */
        $menuRepository = $this->repositoryService->get(Menu::class);
        $menuRepository->save($entity);

        return new RedirectResponse(
            $this->generateUrl($routes['list'])
        );
    }

    public function index(
        array $parameters = []
    ): Response
    {
        /** @var MenuRepository $menuRepository */
        $menuRepository = $this->repositoryService->get(Menu::class);
        $all            = $menuRepository->findAllCode();
        $globals        = $this->twigEnvironment->getGlobals();
        $modal          = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['delete'] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
        $this->adminBtnService->addBtnNew('admin_menu_new');

        $templates = $this->getDomain()->getTemplates();
        if (!array_key_exists('index', $templates)) {
            throw new Exception('Template not found');
        }

        $parameters = array_merge(
            $parameters,
            ['all' => $all]
        );

        return $this->render(
            'admin/menu/index.html.twig',
            $parameters
        );
    }

    public function move(
        Menu $menu,
        array $parameters = []
    ): Response
    {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['list']) || !isset($routes['popupmove'])) {
            throw new Exception('Route list not found');
        }

        /** @var Request $request */
        $request    = $this->requeststack->getCurrentRequest();
        $currentUrl = $this->generateUrl(
            $routes['popupmove'],
            [
                'id' => $menu->getId(),
            ]
        );
        if ('POST' == $request->getMethod()) {
            $this->setPositionEntity($request, Menu::class);
        }

        $this->adminBtnService->addBtnList(
            $routes['list'],
            'Liste',
        );
        $this->adminBtnService->add(
            'btn-admin-save-move',
            'Enregistrer',
            [
                'is'   => 'link-btnadminmove',
                'href' => $currentUrl,
            ]
        );

        $parameters = array_merge(
            $parameters,
            ['menu' => $menu]
        );

        $templates = $this->getDomain()->getTemplates();
        if (!array_key_exists('move', $templates)) {
            throw new Exception('Template not found');
        }

        return $this->render(
            $templates['move'],
            $parameters
        );
    }
}
