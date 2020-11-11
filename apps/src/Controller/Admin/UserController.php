<?php

namespace Labstag\Controller\Admin;

use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\User;
use Labstag\Form\Admin\UserType;
use Labstag\Repository\UserRepository;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(
        PaginatorInterface $paginator,
        Request $request,
        UserRepository $userRepository
    ): Response
    {
        $pagination = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10
        );
        return $this->render(
            'admin/user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user   = new User();
        $form   = $this->createForm(UserType::class, $user);
        $return = $this->newForm($request, $form, $user);
        if ($return) {
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render(
            'admin/user/new.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render(
            'admin/user/show.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form   = $this->createForm(UserType::class, $user);
        $return = $this->editForm($request, $form);
        if ($return) {
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render(
            'admin/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        $this->deleteEntity($request, $user);

        return $this->redirectToRoute('admin_user_index');
    }
}
