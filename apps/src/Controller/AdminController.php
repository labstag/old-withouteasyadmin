<?php

namespace Labstag\Controller;

use Labstag\Form\Admin\FormType;
use Labstag\Lib\AdminControllerLib;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class AdminController extends AdminControllerLib
{
    /**
     * @Route("/", name="admin")
     */
    public function index(): Response
    {
        return $this->render(
            'admin/index.html.twig',
            ['controller_name' => 'AdminController']
        );
    }

    /**
     * @Route("/profil", name="admin_profil")
     */
    public function profil(): Response
    {
        return $this->render(
            'admin/profil/index.html.twig',
            ['controller_name' => 'AdminController']
        );
    }

    /**
     * @Route("/themes", name="admin_themes")
     */
    public function themes(): Response
    {
        $data = [
            'buttons'     => [[]],
            'choice'      => [[]],
            'dateandtime' => [[]],
            'hidden'      => [[]],
            'extra'       => [[]],
            'other'       => [[]],
            'text'        => [[]],
        ];

        $form = $this->createForm(FormType::class, $data);
        return $this->render(
            'admin/form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
