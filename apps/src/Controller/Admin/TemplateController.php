<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Template;
use Labstag\Form\Admin\TemplateType;
use Labstag\Repository\TemplateRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/template")
 */
class TemplateController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_template_index", methods={"GET"})
     */
    public function index(TemplateRepository $templateRepository): Response
    {
        return $this->adminCrudService->list(
            $templateRepository,
            'findAllForAdmin',
            'admin/template/index.html.twig',
            ['new' => 'admin_template_new']
        );
    }

    /**
     * @Route("/new", name="admin_template_new", methods={"GET","POST"})
     */
    public function new(): Response
    {
        return $this->adminCrudService->create(
            new Template(),
            TemplateType::class,
            ['list' => 'admin_template_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_template_show", methods={"GET"})
     */
    public function show(Template $template): Response
    {
        return $this->adminCrudService->read(
            $template,
            'admin/template/show.html.twig',
            [
                'delete' => 'admin_template_delete',
                'list'   => 'admin_template_index',
                'edit'   => 'admin_template_edit',
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_template_edit", methods={"GET","POST"})
     */
    public function edit(Template $template): Response
    {
        return $this->adminCrudService->update(
            TemplateType::class,
            $template,
            [
                'delete' => 'admin_template_delete',
                'list'   => 'admin_template_index',
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_template_delete", methods={"DELETE"})
     */
    public function delete(Template $template): Response
    {
        return $this->adminCrudService->delete(
            $template,
            'admin_template_index'
        );
    }
}
