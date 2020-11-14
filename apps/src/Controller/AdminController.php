<?php

namespace Labstag\Controller;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ProfilType;
use Labstag\Form\Admin\ParamType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Service\DataService;
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
     * @Route("/param", name="admin_param", methods={"GET","POST"})
     */
    public function param(
        Request $request,
        ConfigurationRepository $repository
    ): Response
    {
        $config = $this->dataService->getConfig();
        $form   = $this->createForm(ParamType::class, $config);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $post    = $request->request->get($form->getName());
            $manager = $this->getDoctrine()->getManager();
            foreach ($post as $key => $value) {
                if ('_token' == $key) {
                    continue;
                }

                $configuration = $repository->findOneBy(['name' => $key]);
                if (!$configuration) {
                    $configuration = new Configuration();
                    $configuration->setName($key);
                }

                $configuration->setValue($value);
                $manager->persist($configuration);
            }

            $manager->flush();
            $this->addFlash('success', 'Données sauvegardé');
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
    public function profil(Request $request, Security $security): Response
    {
        $user   = $security->getUser();
        $form   = $this->createForm(ProfilType::class, $user);
        $return = $this->newForm($request, $form, $user);
        if ($return) {
            return $this->redirectToRoute('admin_profil');
        }

        return $this->render(
            'admin/profil.html.twig',
            [

                'entity' => $user,
                'form'   => $form->createView(),
            ]
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
