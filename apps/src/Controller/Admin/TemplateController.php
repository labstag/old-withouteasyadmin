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
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        TemplateRepository $templateRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $templateRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/template/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_template_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $template = new Template();
        $form     = $this->createForm(TemplateType::class, $template);
        $return   = $this->newForm($request, $form, $template);
        if ($return) {
            return $this->redirectToRoute('admin_template_index');
        }

        return $this->render(
            'admin/template/new.html.twig',
            [
                'template' => $template,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_template_show", methods={"GET"})
     */
    public function show(Template $template): Response
    {
        return $this->render(
            'admin/template/show.html.twig',
            ['template' => $template]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_template_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Template $template): Response
    {
        $form   = $this->createForm(TemplateType::class, $template);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_template_index');
        }

        return $this->render(
            'admin/template/edit.html.twig',
            [
                'template' => $template,
                'form'     => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_template_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Template $template): Response
    {
        $this->deleteEntity($request, $template);

        return $this->redirectToRoute('admin_template_index');
    }
}
