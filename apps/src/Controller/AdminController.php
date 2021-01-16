<?php

namespace Labstag\Controller;

use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Event\UserEntityEvent;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ProfilType;
use Labstag\Form\Admin\ParamType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Labstag\Lib\AdminControllerLib;
use Labstag\Manager\UserManager;
use Labstag\Service\AdminBoutonService;
use Labstag\Service\DataService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\EventDispatcher\EventDispatcherInterface;

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
     * @Route("/param", name="admin_param", methods={"GET","POST"})
     */
    public function param(
        Request $request,
        EventDispatcherInterface $dispatcher,
        DataService $dataService,
        AdminBoutonService $adminBoutonService
    ): Response
    {
        $this->headerTitle = 'Paramètres';
        $this->urlHome     = 'admin_param';
        $config            = $dataService->getConfig();
        $form              = $this->createForm(ParamType::class, $config);
        $adminBoutonService->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post = $request->request->get($form->getName());
            $dispatcher->dispatch(new ConfigurationEntityEvent($post));
        }

        return $this->render(
            'admin/param.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/profil", name="admin_profil", methods={"GET","POST"})
     */
    public function profil(
        Security $security,
        UserManager $userManager
    ): Response
    {
        $this->headerTitle = 'Profil';
        $this->urlHome     = 'admin_profil';
        return $this->adminCrudService->update(
            ProfilType::class,
            $security->getUser(),
            [],
            [UserEntityEvent::class],
            $userManager,
            'admin/profil.html.twig'
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
