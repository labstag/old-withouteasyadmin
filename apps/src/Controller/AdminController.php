<?php

namespace Labstag\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ParamType;
use Labstag\Form\Admin\ProfilType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\NoteInterneRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\DataService;
use Labstag\Service\OauthService;
use Labstag\Service\TrashService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/export", name="admin_export")
     */
    public function export(DataService $dataService, LoggerInterface $logger): RedirectResponse
    {
        $config = $dataService->getConfig();
        ksort($config);
        $content = json_encode($config, JSON_PRETTY_PRINT);
        $file    = '../json/config.json';
        if (is_file($file)) {
            try {
                file_put_contents($file, $content);
                $this->flashbag->add(
                    'success',
                    'Données exporté'
                );
            } catch (Exception $exception) {
                $this->setErrorLogger($exception, $logger);
                $this->addFlash(
                    'danger',
                    sprintf(
                        "Problème d'enregistrement du fichier %s",
                        $file
                    )
                );
            }
        }

        return $this->redirect($this->generateUrl('admin_param'));
    }

    /**
     * @Route("/", name="admin")
     */
    public function index(NoteInterneRepository $noteInterneRepo): Response
    {
        $noteinternes = $noteInterneRepo->findPublier();

        return $this->render(
            'admin/index.html.twig',
            ['noteinternes' => $noteinternes]
        );
    }

    /**
     * @Route("/oauth", name="admin_oauth")
     */
    public function oauth(OauthService $oauthService): Response
    {
        $this->headerTitle = 'Oauth';
        $this->urlHome     = 'admin_oauth';
        $types             = $oauthService->getConfigProvider();

        return $this->render(
            'admin/oauth.html.twig',
            ['types' => $types]
        );
    }

    /**
     * @Route("/param", name="admin_param", methods={"GET","POST"})
     */
    public function param(
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
        AttachmentRepository $repository,
        DataService $dataService,
        CacheInterface $cache
    ): Response
    {
        $this->modalAttachmentDelete();
        $images = [
            'image'   => $repository->getImageDefault(),
            'favicon' => $repository->getFavicon(),
        ];

        foreach ($images as $key => $value) {
            if (is_null($value)) {
                $images[$key] = new Attachment();
                $images[$key]->setCode($key);
                $entityManager->persist($images[$key]);
                $entityManager->flush();
            }
        }

        $this->headerTitle = 'Paramètres';
        $this->urlHome     = 'admin_param';
        $config            = $dataService->getConfig();
        $tab               = $this->getParameter('metatags');
        foreach ($tab as $index) {
            $config[$index] = [
                $config[$index],
            ];
        }

        $form = $this->createForm(ParamType::class, $config);
        $this->btnInstance->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->setUpload($request, $images);
            $cache->delete('configuration');
            $post = $request->request->get($form->getName());
            $dispatcher->dispatch(new ConfigurationEntityEvent($post));
        }

        $this->btnInstance->add(
            'btn-admin-header-export',
            'Exporter',
            [
                'href' => $this->router->generate('admin_export'),
            ]
        );

        return $this->render(
            'admin/param.html.twig',
            [
                'images' => $images,
                'form'   => $form->createView(),
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

        $globals        = $this->twig->getGlobals();
        $modal          = isset($globals['modal']) ? $globals['modal'] : [];
        $modal['empty'] = true;
        $token          = $this->csrfTokenManager->getToken('emptyall')->getValue();
        if ($this->isRouteEnable('api_action_emptyall')) {
            $modal['emptyall'] = true;
            $this->btnInstance->add(
                'btn-admin-header-emptyall',
                'Tout vider',
                [
                    'is'             => 'link-btnadminemptyall',
                    'data-bs-toggle' => 'modal',
                    'data-bs-target' => '#emptyall-modal',
                    'data-token'     => $token,
                    'data-redirect'  => $this->router->generate('admin_trash'),
                    'data-url'       => $this->router->generate('api_action_emptyall'),
                ]
            );
        }

        $this->twig->addGlobal('modal', $modal);
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

    private function setUpload(Request $request, array $images)
    {
        $all   = $request->files->all();
        $files = $all['param'];
        $paths = [
            'image'   => $this->getParameter('file_directory'),
            'favicon' => $this->getParameter('kernel.project_dir').'/public',
        ];
        foreach ($paths as $path) {
            if (is_dir($path)) {
                continue;
            }

            mkdir($path, 0777, true);
        }

        foreach ($files as $key => $file) {
            if (is_null($file)) {
                continue;
            }

            $attachment = $images[$key];
            $old        = clone $attachment;
            $filename   = $file->getClientOriginalName();
            $path       = $paths[$key];
            $filename   = ('favicon' == $key) ? 'favicon.ico' : $filename;
            $file->move(
                $path,
                $filename
            );
            $file = $path.'/'.$filename;
            $attachment->setMimeType(mime_content_type($file));
            $attachment->setSize(filesize($file));
            $size = getimagesize($file);
            $attachment->setDimensions(is_array($size) ? $size : []);
            $attachment->setName(
                str_replace(
                    $this->getParameter('kernel.project_dir').'/public/',
                    '',
                    $file
                )
            );
            $this->attachmentRH->handle($old, $attachment);
        }
    }
}
