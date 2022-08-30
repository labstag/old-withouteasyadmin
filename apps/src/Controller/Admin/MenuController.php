<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\Menu\LinkType;
use Labstag\Form\Admin\Menu\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MenuRepository;
use Labstag\RequestHandler\MenuRequestHandler;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route(path: '/admin/menu')]
class MenuController extends AdminControllerLib
{
    #[Route(path: '/add', name: 'admin_menu_add', methods: ['GET', 'POST'])]
    public function add(AttachFormService $service, Request $request, MenuRequestHandler $requestHandler): Response
    {
        $get = $request->query->all();
        $url = $this->generateUrl('admin_menu_index');
        if (!isset($get['id'])) {
            return new RedirectResponse($url);
        }

        $parent = $this->getRepository(Menu::class)->find($get['id']);
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
            $service,
            $requestHandler,
            LinkType::class,
            $menu,
            'admin/menu/form.html.twig'
        );
    }

    #[Route(path: '/divider/{id}', name: 'admin_menu_divider')]
    public function divider(Menu $menu, MenuRequestHandler $requestHandler): RedirectResponse
    {
        $entity    = new Menu();
        $oldEntity = clone $entity;
        $children  = $menu->getChildren();
        $position  = is_countable($children) ? count($children) : 0;
        $entity->setPosition($position + 1);
        $entity->setSeparateur(true);
        $entity->setParent($menu);
        $requestHandler->handle($oldEntity, $entity);

        return new RedirectResponse(
            $this->generateUrl('admin_menu_index')
        );
    }

    #[Route(path: '/update/{id}', name: 'admin_menu_update', methods: ['GET', 'POST'])]
    public function edit(AttachFormService $service, Menu $menu, MenuRequestHandler $requestHandler)
    {
        $this->modalAttachmentDelete();
        $form = empty($menu->getClef()) ? LinkType::class : PrincipalType::class;
        $data = [$menu->getData()];
        $menu->setData($data);

        return $this->form(
            $service,
            $requestHandler,
            $form,
            $menu,
            'admin/menu/form.html.twig'
        );
    }

    #[Route(path: '/', name: 'admin_menu_index', methods: ['GET'])]
    public function index(
        Environment $twig,
        MenuRepository $repository
    )
    {
        $all             = $repository->findAllCode();
        $globals         = $twig->getGlobals();
        $modal           = $globals['modal'] ?? [];
        $modal['delete'] = true;
        $twig->addGlobal('modal', $modal);
        $this->btnInstance()->addBtnNew('admin_menu_new');

        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    #[Route(path: '/move/{id}', name: 'admin_menu_move', methods: ['GET', 'POST'])]
    public function move(Menu $menu, Request $request)
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

        $this->btnInstance()->addBtnList(
            'admin_menu_index',
            'Liste',
        );
        $this->btnInstance()->add(
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

    #[Route(path: '/new', name: 'admin_menu_new', methods: ['GET', 'POST'])]
    public function new(AttachFormService $service, MenuRequestHandler $requestHandler): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            PrincipalType::class,
            new Menu(),
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_menu_trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->listOrTrash(
            Menu::class,
            'admin/menu/trash.html.twig',
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_menu_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_menu_index',
            'new'     => 'admin_menu_new',
            'restore' => 'api_action_restore',
            'trash'   => 'admin_menu_trash',
        ];
    }

    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('menu.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_index',
                ],
                [
                    'title' => $this->translator->trans('menu.add', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_add',
                ],
                [
                    'title' => $this->translator->trans('menu.divider', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_divider',
                ],
                [
                    'title' => $this->translator->trans('menu.move', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_move',
                ],
                [
                    'title' => $this->translator->trans('menu.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_new',
                ],
                [
                    'title' => $this->translator->trans('menu.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_trash',
                ],
                [
                    'title' => $this->translator->trans('menu.update', [], 'admin.breadcrumb'),
                    'route' => 'admin_menu_update',
                ],
            ]
        );
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_menu' => $this->translator->trans('menu.title', [], 'admin.header'),
            ]
        );
    }
}
