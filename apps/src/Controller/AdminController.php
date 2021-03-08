<?php

namespace Labstag\Controller;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ParamType;
use Labstag\Form\Admin\ProfilType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\DataService;
use Labstag\Service\TrashService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;

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
            'admin/index.html.twig'
        );
    }

    /**
     * @Route("/trash", name="admin_trash")
     * @IgnoreSoftDelete
     */
    public function trash(TrashService $trashService): Response
    {
        $this->headerTitle = 'Trash';
        $this->urlHome     = 'admin_trash';
        $all               = $trashService->all();
        if (0 == count($all)) {
            $this->addFlash(
                'danger',
                'La corbeille est vide'
            );

            return $this->redirect($this->generateUrl('admin'));
        }

        $this->twig->addGlobal(
            'modalEmpty',
            true
        );
        $token = $this->csrfTokenManager->getToken('emptyall')->getValue();
        if ($this->isRouteEnable('api_action_emptyall')) {
            $this->twig->addGlobal(
                'modalEmptyAll',
                true
            );
            $this->btnInstance->add(
                'btn-admin-header-emptyall',
                'Tout vider',
                [
                    'is'            => 'link-btnadminemptyall',
                    'data-toggle'   => 'modal',
                    'data-target'   => '#emptyallModal',
                    'data-token'    => $token,
                    'data-redirect' => $this->router->generate('admin_trash'),
                    'data-url'      => $this->router->generate('api_action_emptyall'),
                ]
            );
        }

        $this->btnInstance->addViderSelection(
            [
                'redirect' => [
                    'href'   => 'admin_trash',
                    'params' => [],
                ],
                'url'      => [
                    'href'   => 'api_action_empties',
                    'params' => [],
                ],
            ],
            'empties'
        );

        return $this->render(
            'admin/trash.html.twig',
            ['trash' => $all]
        );
    }

    /**
     * @Route("/param", name="admin_param", methods={"GET","POST"})
     */
    public function param(
        Request $request,
        EventDispatcherInterface $dispatcher,
        DataService $dataService,
        CacheInterface $cache
    ): Response
    {
        $this->headerTitle = 'Paramètres';
        $this->urlHome     = 'admin_param';
        $config            = $dataService->getConfig();
        $tab               = [
            'disclaimer',
            'meta',
        ];
        foreach ($tab as $index) {
            $config[$index] = [
                $config[$index],
            ];
        }

        dump($config);

        $form = $this->createForm(ParamType::class, $config);
        $this->btnInstance->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $cache->delete('configuration');
            $post = $request->request->get($form->getName());
            dump($post);
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
    public function profil(Security $security, UserRequestHandler $requestHandler): Response
    {
        $this->headerTitle = 'Profil';
        $this->urlHome     = 'admin_profil';
        $this->modalAttachmentDelete();

        return $this->update(
            ProfilType::class,
            $security->getUser(),
            $requestHandler,
            [],
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
