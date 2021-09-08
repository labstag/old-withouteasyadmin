<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Menu;
use Labstag\Form\Admin\Menu\LinkType;
use Labstag\Form\Admin\Menu\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\MenuRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
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
     * @Route("/add", name="admin_menu_add", methods={"GET", "POST"})
     */
    public function add(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Request $request,
        MenuRequestHandler $requestHandler,
        MenuRepository $repository
    ): Response
    {
        $get = $request->query->all();
        $url = $this->router->generate('admin_menu_index');
        if (!isset($get['id'])) {
            return new RedirectResponse($url);
        }

        $parent = $repository->find($get['id']);
        if (!$parent instanceof Menu) {
            return new RedirectResponse($url);
        }

        $menu = new Menu();
        $data = [$menu->getData()];
        $menu->setClef(null);
        $menu->setData($data);
        $menu->setSeparateur(false);
        $menu->setPosition(count($parent->getChildren()));
        $menu->setParent($parent);

        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            $menu,
            LinkType::class,
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
     * @Route("/update/{id}", name="admin_menu_update", methods={"GET", "POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Menu $menu,
        MenuRequestHandler $requestHandler
    )
    {
        $this->modalAttachmentDelete();
        $form = empty($menu->getClef()) ? LinkType::class : PrincipalType::class;
        $data = [$menu->getData()];
        $menu->setData($data);

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            $form,
            $menu,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_menu_index',
            ],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/", name="admin_menu_index", methods={"GET"})
     */
    public function index(
        MenuRepository $repository
    )
    {
        $all     = $repository->findAllCode();
        $globals = $this->twig->getGlobals();
        $modal   = $globals['modal'] ?? [];

        $modal['delete'] = true;
        $this->twig->addGlobal('modal', $modal);
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
        MenuRepository $repository
    )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $currentUrl    = $this->router->generate(
            'admin_menu_move',
            [
                'id' => $menu->getId(),
            ]
        );

        if ('POST' == $request->getMethod()) {
            $data = $request->request->get('position');
            if (!empty($data)) {
                $data = json_decode($data, true);
            }

            if (is_array($data)) {
                foreach ($data as $row) {
                    $id       = $row['id'];
                    $position = $row['position'];
                    $entity   = $repository->find($id);
                    $entity->setPosition($position);
                    $entityManager->persist($entity);
                }

                $entityManager->flush();
            }
        }

        $breadcrumb = ['Move' => $currentUrl];
        $this->addBreadcrumbs($breadcrumb);
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
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        MenuRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Menu(),
            PrincipalType::class,
            ['list' => 'admin_menu_index'],
            'admin/menu/form.html.twig'
        );
    }

    /**
     * @Route("/trash", name="admin_menu_trash", methods={"GET"})
     *
     * @IgnoreSoftDelete
     */
    public function trash(MenuRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/menu/trash.html.twig',
            [
                'new'   => 'admin_menu_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_menu_trash',
                'list'  => 'admin_menu_index',
            ],
            [
                'list'    => 'admin_menu_index',
                'edit'    => 'admin_menu_edit',
                'delete'  => 'api_action_delete',
                'destroy' => 'api_action_destroy',
                'restore' => 'api_action_restore',
            ]
        );
    }
}
