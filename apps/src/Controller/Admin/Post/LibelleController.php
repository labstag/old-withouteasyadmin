<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Libelle;
use Labstag\Form\Admin\Post\LibelleType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\Service\GuardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/post/libelle")
 */
class LibelleController extends AdminControllerLib
{

    protected string $headerTitle = 'Libellé';

    protected string $urlHome = 'admin_postlibelle_index';

    /**
     * @Route("/{id}/edit", name="admin_postlibelle_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        GuardService $guarService,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Libelle $libelle,
        LibelleRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $guarService,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            LibelleType::class,
            $libelle,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_postlibelle_index',
                'show'   => 'admin_postlibelle_show',
            ]
        );
    }

    /**
     * @Route("/trash", name="admin_postlibelle_trash", methods={"GET"})
     * @Route("/", name="admin_postlibelle_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(LibelleRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/post/libelle/index.html.twig',
            [
                'new'   => 'admin_postlibelle_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_postlibelle_trash',
                'list'  => 'admin_postlibelle_index',
            ],
            [
                'list'     => 'admin_postlibelle_index',
                'show'     => 'admin_postlibelle_show',
                'preview'  => 'admin_postlibelle_preview',
                'edit'     => 'admin_postlibelle_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_postlibelle_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        LibelleRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Libelle(),
            LibelleType::class,
            ['list' => 'admin_postlibelle_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_postlibelle_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_postlibelle_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        GuardService $guardService,
        Libelle $libelle
    ): Response
    {
        return $this->renderShowOrPreview(
            $guardService,
            $libelle,
            'admin/post/libelle/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_postlibelle_edit',
                'list'    => 'admin_postlibelle_index',
                'trash'   => 'admin_postlibelle_trash',
            ]
        );
    }
}
