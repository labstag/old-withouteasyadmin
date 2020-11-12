<?php

namespace Labstag\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="admin_profil")
     */
    public function index(): Response
    {
        return $this->render(
            'admin/profil.html.twig',
            ['controller_name' => 'ProfilController']
        );
    }
}
