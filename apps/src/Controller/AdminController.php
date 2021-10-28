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
use Labstag\Repository\MemoRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Service\AttachFormService;
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
    public function export(
        DataService $dataService,
        LoggerInterface $logger
    ): RedirectResponse
    {
        $config = $dataService->getConfig();
        ksort($config);
        $content = json_encode($config, JSON_PRETTY_PRINT);
        $file    = '../json/config.json';
        if (is_file($file)) {
            try {
                file_put_contents($file, $content);
                $this->flashBagAdd(
                    'success',
                    $this->translator->trans('admin.flashbag.data.export.success')
                );
            } catch (Exception $exception) {
                $this->setErrorLogger($exception, $logger);
                $paramtrans = ['%file%' => $file];

                $msg = $this->translator->trans('admin.flashbag.data.export.fail', $paramtrans);
                $this->flashBagAdd('danger', $msg);
            }
        }

        return $this->redirectToRoute('admin_param');
    }

    /**
     * @Route("/", name="admin")
     */
    public function index(MemoRepository $noteInterneRepo): Response
    {
        $memos = $noteInterneRepo->findPublier();

        return $this->render(
            'admin/index.html.twig',
            ['memos' => $memos]
        );
    }

    /**
     * @Route("/oauth", name="admin_oauth")
     */
    public function oauth(OauthService $oauthService): Response
    {
        $types = $oauthService->getConfigProvider();

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
            if (!is_null($value)) {
                continue;
            }

            $images[$key] = new Attachment();
            $images[$key]->setCode($key);
            $entityManager->persist($images[$key]);
            $entityManager->flush();
        }

        $config = $dataService->getConfig();
        $tab    = $this->getParameter('metatags');
        foreach ($tab as $index) {
            $config[$index] = [
                $config[$index],
            ];
        }

        $form = $this->createForm(ParamType::class, $config);
        $this->btnInstance()->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->setUpload($request, $images);
            $cache->delete('configuration');
            $post = $request->request->get($form->getName());
            $dispatcher->dispatch(new ConfigurationEntityEvent($post));
        }

        $this->btnInstance()->add(
            'btn-admin-header-export',
            'Exporter',
            [
                'href' => $this->generateUrl('admin_export'),
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
    public function profil(
        AttachFormService $service,
        Security $security,
        UserRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            ProfilType::class,
            $security->getUser(),
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
     * @Route("/trash",  name="admin_trash")
     * @IgnoreSoftDelete
     */
    public function trash(
        TrashService $trashService
    ): Response
    {
        $all = $trashService->all();
        if (0 == count($all)) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('admin.flashbag.trash.empty')
            );

            return $this->redirectToRoute('admin');
        }

        $globals        = $this->get('twig')->getGlobals();
        $modal          = $globals['modal'] ?? [];
        $modal['empty'] = true;
        if ($this->isRouteEnable('api_action_emptyall')) {
            $token             = $this->get('security.csrf.token_manager')->getToken('emptyall')->getValue();
            $modal['emptyall'] = true;
            $this->btnInstance()->add(
                'btn-admin-header-emptyall',
                'Tout vider',
                [
                    'is'       => 'link-btnadminemptyall',
                    'token'    => $token,
                    'redirect' => $this->generateUrl('admin_trash'),
                    'url'      => $this->generateUrl('api_action_emptyall'),
                ]
            );
        }

        $this->get('twig')->addGlobal('modal', $modal);
        $this->btnInstance()->addViderSelection(
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

    protected function setBreadcrumbsPageAdminOauth(): array
    {
        return [
            [
                'title'        => $this->translator->trans('oauth.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_oauth',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminParam(): array
    {
        return [
            [
                'title'        => $this->translator->trans('param.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_param',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminProfil(): array
    {
        return [
            [
                'title'        => $this->translator->trans('profil.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_profil',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('trash.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_oauth'  => $this->translator->trans('oauth.title', [], 'admin.header'),
                'admin_param'  => $this->translator->trans('param.title', [], 'admin.header'),
                'admin_profil' => $this->translator->trans('profil.title', [], 'admin.header'),
                'admin_trash'  => $this->translator->trans('trash.title', [], 'admin.header'),
            ]
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
