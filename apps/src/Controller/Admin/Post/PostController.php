<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post")
 */
class PostController extends AdminControllerLib
{

    protected string $headerTitle = 'Post';

    protected string $urlHome = 'admin_post_index';

    /**
     * @Route("/trash", name="admin_post_trash", methods={"GET"})
     * @Route("/", name="admin_post_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EditoRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/post/index.html.twig',
            [
                'new'   => 'admin_post_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_post_trash',
                'list'  => 'admin_post_index',
            ],
            [
                'list'     => 'admin_post_index',
                'show'     => 'admin_post_show',
                'preview'  => 'admin_post_preview',
                'edit'     => 'admin_post_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_post_new", methods={"GET","POST"})
     */
    public function new(EditoRequestHandler $requestHandler): Response
    {
        return $this->create(
            new Edito(),
            EditoType::class,
            $requestHandler,
            ['list' => 'admin_post_index'],
            'admin/post/form.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_post_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_post_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Edito $edito): Response
    {
        return $this->renderShowOrPreview(
            $edito,
            'admin/post/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_post_edit',
                'list'    => 'admin_post_index',
                'trash'   => 'admin_post_trash',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_post_edit", methods={"GET","POST"})
     */
    public function edit(Edito $edito, EditoRequestHandler $requestHandler): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            EditoType::class,
            $edito,
            $requestHandler,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_post_index',
                'show'   => 'admin_post_show',
            ],
            'admin/post/form.html.twig'
        );
    }
}
