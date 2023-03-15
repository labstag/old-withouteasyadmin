<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Menu;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MenuRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route(path: '/admin/menu', name: 'admin_menu_')]
class MenuController extends AdminControllerLib
{
    #[Route(path: '/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(
        Request $request,
        MenuRepository $menuRepository
    ): Response
    {
        $get = $request->query->all();
        $url = $this->generateUrl('admin_menu_index');
        if (!isset($get['id'])) {
            return new RedirectResponse($url);
        }

        $parent = $menuRepository->find($get['id']);
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

        return $this->form(
            $this->getDomainEntity(),
            $menu
        );
    }

    #[Route(path: '/divider/{id}', name: 'divider')]
    public function divider(
        Menu $menu,
        MenuRepository $menuRepository
    ): RedirectResponse
    {
        $entity   = new Menu();
        $children = $menu->getChildren();
        $position = is_countable($children) ? count($children) : 0;
        $entity->setPosition($position + 1);
        $entity->setSeparateur(true);
        $entity->setParent($menu);

        $menuRepository->add($entity);

        return new RedirectResponse(
            $this->generateUrl('admin_menu_index')
        );
    }

    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'])]
    public function edit(
        Menu $menu
    ): Response
    {
        $this->modalAttachmentDelete();
        $data             = [$menu->getData()];
        $data[0]['param'] = isset($data[0]['params']) ? json_encode($data[0]['params'], JSON_THROW_ON_ERROR) : '';
        $menu->setData($data);

        return $this->form(
            $this->getDomainEntity(),
            $menu
        );
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(
        Environment $twigEnvironment,
        MenuRepository $menuRepository
    ): Response
    {
        $all     = $menuRepository->findAllCode();
        $globals = $twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['delete'] = true;
        $twigEnvironment->addGlobal('modal', $modal);
        $this->adminBtnService->addBtnNew('admin_menu_new');

        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    #[Route(path: '/move/{id}', name: 'move', methods: ['GET', 'POST'])]
    public function move(Menu $menu, Request $request): Response
    {
        $currentUrl = $this->generateUrl(
            'admin_menu_move',
            [
                'id' => $menu->getId(),
            ]
        );
        if ('POST' == $request->getMethod()) {
            $this->setPositionEntity($request, Menu::class);
        }

        $this->adminBtnService->addBtnList(
            'admin_menu_index',
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

        return $this->render(
            'admin/menu/move.html.twig',
            ['menu' => $menu]
        );
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            new Menu()
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/menu/trash.html.twig',
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Menu::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
