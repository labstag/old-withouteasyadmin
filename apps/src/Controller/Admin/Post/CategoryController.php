<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Category;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/category")
 */
class CategoryController extends AdminControllerLib
{
    /**
     * @Route("/trash",  name="admin_category_trash", methods={"GET"})
     * @Route("/",       name="admin_category_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(CategoryRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/post/category/index.html.twig',
            [
                'new'   => 'admin_category_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_category_trash',
                'list'  => 'admin_category_index',
            ],
            [
                'list'     => 'admin_category_index',
                'preview'  => 'admin_category_preview',
                'edit'     => 'admin_category_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }
}
