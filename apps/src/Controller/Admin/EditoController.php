<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Repository\EditoRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edito")
 */
class EditoController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_edito_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        EditoRepository $editoRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $editoRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/edito/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_edito_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $edito  = new Edito();
        $form   = $this->createForm(EditoType::class, $edito);
        $return = $this->newForm($request, $form, $edito);
        if ($return) {
            return $this->redirectToRoute('admin_edito_index');
        }

        return $this->render(
            'admin/edito/new.html.twig',
            [
                'edito' => $edito,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_show", methods={"GET"})
     */
    public function show(Edito $edito): Response
    {
        return $this->render(
            'admin/edito/show.html.twig',
            ['edito' => $edito]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_edito_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Edito $edito): Response
    {
        $form   = $this->createForm(EditoType::class, $edito);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_edito_index');
        }

        return $this->render(
            'admin/edito/edit.html.twig',
            [
                'edito' => $edito,
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Edito $edito): Response
    {
        $this->deleteEntity($request, $edito);

        return $this->redirectToRoute('admin_edito_index');
    }
}
