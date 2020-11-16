<?php

namespace Labstag\Controller\Admin;

use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\NoteInterne;
use Labstag\Form\Admin\NoteInterneType;
use Labstag\Repository\NoteInterneRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/noteinterne")
 */
class NoteInterneController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_noteinterne_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        NoteInterneRepository $repository
    ): Response
    {
        $pagination = $paginator->paginate(
            $repository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/note_interne/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $noteInterne = new NoteInterne();
        $form        = $this->createForm(NoteInterneType::class, $noteInterne);
        $return      = $this->newForm($request, $form, $noteInterne);
        if ($return) {
            return $this->redirectToRoute('admin_noteinterne_index');
        }

        return $this->render(
            'admin/note_interne/new.html.twig',
            [
                'note_interne' => $noteInterne,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_show", methods={"GET"})
     */
    public function show(NoteInterne $noteInterne): Response
    {
        return $this->render(
            'admin/note_interne/show.html.twig',
            ['note_interne' => $noteInterne]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_noteinterne_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(Request $request, NoteInterne $noteInterne): Response
    {
        $form   = $this->createForm(NoteInterneType::class, $noteInterne);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_noteinterne_index');
        }

        return $this->render(
            'admin/note_interne/edit.html.twig',
            [
                'note_interne' => $noteInterne,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_noteinterne_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NoteInterne $noteInterne): Response
    {
        $this->deleteEntity($request, $noteInterne);

        return $this->redirectToRoute('admin_noteinterne_index');
    }
}
