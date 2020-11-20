<?php

namespace Labstag\Controller;

use Labstag\Service\MailerService;
use Labstag\Entity\Configuration;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ProfilType;
use Labstag\Form\Admin\ParamType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ConfigurationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Labstag\Service\AdminBoutonService;
use Labstag\Service\AdminCrudService;
use Labstag\Service\DataService;

/**
 * @Route("/admin")
 */
class AdminController extends AdminControllerLib
{

    protected MailerService $mailerService;

    public function __construct(
        DataService $dataService,
        AdminBoutonService $adminBoutonService,
        AdminCrudService $adminCrudService,
        MailerService $mailerService
    )
    {
        $this->mailerService = $mailerService;
        parent::__construct(
            $dataService,
            $adminBoutonService,
            $adminCrudService
        );
    }

    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        $email = $this->mailerService->createEmail(
            [
                'html' => 'mails/test.html.twig',
                'txt'  => 'mails/test.text.twig',
            ]
        );
        $email->to('you@example.com');

        $this->mailerService->send($email);

        return '';
    }

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
        $this->adminBoutonService->addBtnSave('Sauvegarder');
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
                if (!($configuration instanceof Configuration)) {
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
    public function profil(Security $security): Response
    {
        return $this->adminCrudService->update(
            ProfilType::class,
            $security->getUser()
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
