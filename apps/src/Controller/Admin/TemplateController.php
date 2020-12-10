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
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/admin/template")
 */
class TemplateController extends AdminControllerLib
{

    protected string $headerTitle = 'Template';

    protected string $urlHome = 'admin_template_index';
    /**
     * @Route("/", name="admin_template_index", methods={"GET"})
     */
    public function index(TemplateRepository $templateRepository): Response
    {
        return $this->adminCrudService->list(
            $templateRepository,
            'findAllForAdmin',
            'admin/template/index.html.twig',
            ['new' => 'admin_template_new'],
            [
                'list'   => 'admin_template_index',
                'show'   => 'admin_template_show',
                'edit'   => 'admin_template_edit',
                'delete' => 'admin_template_delete',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_template_new", methods={"GET","POST"})
     */
    public function new(RouterInterface $router): Response
    {
        $breadcrumb = [
            'New' => $router->generate(
                'admin_template_new'
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->create(
            new Template(),
            TemplateType::class,
            ['list' => 'admin_template_index']
        );
    }

    /**
     * @Route("/{id}", name="admin_template_show", methods={"GET"})
     */
    public function show(Template $template, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Show' => $router->generate(
                'admin_template_show',
                [
                    'id' => $template->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
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
    public function edit(Template $template, RouterInterface $router): Response
    {
        $breadcrumb = [
            'Edit' => $router->generate(
                'admin_template_edit',
                [
                    'id' => $template->getId(),
                ]
            ),
        ];
        $this->setBreadcrumbs($breadcrumb);
        return $this->adminCrudService->update(
            TemplateType::class,
            $template,
            [
                'delete' => 'admin_template_delete',
                'list'   => 'admin_template_index',
                'show'   => 'admin_template_show',
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="admin_template_delete", methods={"POST"})
     */
    public function delete(Template $template): Response
    {
        return $this->adminCrudService->delete($template);
    }
}
