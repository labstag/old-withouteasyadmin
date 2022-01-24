<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\Menu\LinkType;
use Labstag\Form\Admin\Menu\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\MenuRequestHandler;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/admin/menu")
 */
class MenuController extends AdminControllerLib
{
    /**
     * @Route("/add", name="admin_menu_add", methods={"GET", "POST"})
     */
    public function add(
        AttachFormService $service,
        Request $request,
        MenuRequestHandler $requestHandler
    ): Response
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

    /**
     * @Route("/divider/{id}", name="admin_menu_divider")
     */
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

    /**
     * @Route("/update/{id}", name="admin_menu_update", methods={"GET", "POST"})
     */
    public function edit(
        AttachFormService $service,
        Menu $menu,
        MenuRequestHandler $requestHandler
    )
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

    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(
        Environment $twig
    )
    {
        $all     = $this->getRepository(Menu::class)->findAllCode();
        $globals = $twig->getGlobals();
        $modal   = $globals['modal'] ?? [];

        $modal['delete'] = true;
        $twig->addGlobal('modal', $modal);
        $this->btnInstance()->addBtnNew('admin_menu_new');

        return $this->render(
            'admin/menu/index.html.twig',
            ['all' => $all]
        );
    }

    /**
     * @Route("/move/{id}", name="admin_menu_move", methods={"GET", "POST"})
     */
    public function move(
        Menu $menu,
        Request $request,
        EntityManagerInterface $entityManager
    )
    {
        $currentUrl = $this->generateUrl(
            'admin_menu_move',
            [
                'id' => $menu->getId(),
            ]
        );

        if ('POST' == $request->getMethod()) {
            $data = $request->request->all('position');
            if (!empty($data)) {
                $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            }

            if (is_array($data)) {
                foreach ($data as $row) {
                    $id       = $row['id'];
                    $position = intval($row['position']);
                    $entity   = $this->getRepository(Menu::class)->find($id);
                    if (!is_null($entity)) {
                        $entity->setPosition($position + 1);
                        $entityManager->persist($entity);
                    }
                }

                $entityManager->flush();
            }
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

    /**
     * @Route("/new", name="admin_menu_new", methods={"GET", "POST"})
     */
    public function new(
        AttachFormService $service,
        MenuRequestHandler $requestHandler
    ): Response
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
     * @Route("/trash", name="admin_menu_trash", methods={"GET"})
     *
     * @IgnoreSoftDelete
     */
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

    protected function setBreadcrumbsPageAdminMenu(): array
    {
        return [
            [
                'title'        => $this->translator->trans('menu.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuAdd(): array
    {
        return [
            [
                'title'        => $this->translator->trans('menu.add', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_add',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuDivider(): array
    {
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('menu.divider', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_divider',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuMove(): array
    {
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('menu.move', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_move',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('menu.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('menu.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminMenuUpdate(): array
    {
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('menu.update', [], 'admin.breadcrumb'),
                'route'        => 'admin_menu_update',
                'route_params' => $routeParams,
            ],
        ];
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
