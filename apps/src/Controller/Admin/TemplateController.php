<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Template;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\TemplateRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\Service\GuardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/template")
 */
class TemplateController extends AdminControllerLib
{

    protected string $headerTitle = 'Template';

    protected string $urlHome = 'admin_template_index';

    /**
     * @Route("/{id}/edit", name="admin_template_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        GuardService $guarService,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Template $template,
        TemplateRequestHandler $requestHandler
    ): Response
    {
        return $this->update(
            $uploadAnnotReader,
            $guarService,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            TemplateType::class,
            $template,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_template_index',
                'show'   => 'admin_template_show',
            ]
        );
    }

    /**
     * @Route("/trash", name="admin_template_trash", methods={"GET"})
     * @Route("/", name="admin_template_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(TemplateRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/template/index.html.twig',
            [
                'new'   => 'admin_template_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_template_trash',
                'list'  => 'admin_template_index',
            ],
            [
                'list'    => 'admin_template_index',
                'show'    => 'admin_template_show',
                'preview' => 'admin_template_preview',
                'edit'    => 'admin_template_edit',
                'delete'  => 'api_action_delete',
                'destroy' => 'api_action_destroy',
                'restore' => 'api_action_restore',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_template_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        TemplateRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Template(),
            TemplateType::class,
            ['list' => 'admin_template_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_template_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_template_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(Template $template): Response
    {
        return $this->renderShowOrPreview(
            $template,
            'admin/template/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'list'    => 'admin_template_index',
                'edit'    => 'admin_template_edit',
                'trash'   => 'admin_template_trash',
            ]
        );
    }
}
