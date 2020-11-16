<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\AdresseUser;
use Labstag\Form\Admin\AdresseUserType;
use Labstag\Repository\AdresseUserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/adresse")
 */
class AdresseUserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_adresseuser_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        AdresseUserRepository $repository
    ): Response
    {
        $pagination = $paginator->paginate(
            $repository->findAllForAdmin(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/adresse_user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_adresseuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adresseUser = new AdresseUser();
        $form        = $this->createForm(AdresseUserType::class, $adresseUser);
        $return      = $this->newForm($request, $form, $adresseUser);
        if ($return) {
            return $this->redirectToRoute('admin_adresseuser_index');
        }

        return $this->render(
            'admin/adresse_user/new.html.twig',
            [
                'adresse_user' => $adresseUser,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_adresseuser_show", methods={"GET"})
     */
    public function show(AdresseUser $adresseUser): Response
    {
        return $this->render(
            'admin/adresse_user/show.html.twig',
            ['adresse_user' => $adresseUser]
        );
    }

    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_adresseuser_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(Request $request, AdresseUser $adresseUser): Response
    {
        $form   = $this->createForm(AdresseUserType::class, $adresseUser);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_adresseuser_index');
        }

        return $this->render(
            'admin/adresse_user/edit.html.twig',
            [
                'adresse_user' => $adresseUser,
                'form'         => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_adresseuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdresseUser $adresseUser): Response
    {
        $this->deleteEntity($request, $adresseUser);

        return $this->redirectToRoute('admin_adresseuser_index');
    }
}
