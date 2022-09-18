<?php

namespace Labstag\Controller;

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
use Labstag\Service\DataService;
use Labstag\Service\OauthService;
use Labstag\Service\TrashService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

#[Route(path: '/admin')]
class AdminController extends AdminControllerLib
{
    #[Route(path: '/export', name: 'admin_export')]
    public function export(DataService $dataService): RedirectResponse
    {
        $config = $dataService->getConfig();
        ksort($config);
        $content = json_encode($config, JSON_PRETTY_PRINT);
        $file    = '../json/config.json';
        if (is_file($file)) {
            try {
                file_put_contents($file, $content);
                $this->sessionService->flashBagAdd(
                    'success',
                    $this->translator->trans('admin.flashbag.data.export.success')
                );
            } catch (Exception $exception) {
                $this->errorService->set($exception);
                $paramtrans = ['%file%' => $file];

                $msg = $this->translator->trans('admin.flashbag.data.export.fail', $paramtrans);
                $this->sessionService->flashBagAdd('danger', $msg);
            }
        }

        return $this->redirectToRoute('admin_param');
    }

    #[Route(path: '/paragraph', name: 'admin_paragraph', methods: ['GET'])]
    public function iframe(): Response
    {
        return $this->render('admin/paragraph/iframe.html.twig');
    }

    #[Route(path: '/', name: 'admin')]
    public function index(
        MemoRepository $memoRepository
    ): Response
    {
        $memos = $memoRepository->findPublier();

        return $this->render(
            'admin/index.html.twig',
            ['memos' => $memos]
        );
    }

    #[Route(path: '/oauth', name: 'admin_oauth')]
    public function oauth(OauthService $oauthService): Response
    {
        $types = $oauthService->getConfigProvider();

        return $this->render(
            'admin/oauth.html.twig',
            ['types' => $types]
        );
    }

    #[Route(path: '/param', name: 'admin_param', methods: ['GET', 'POST'])]
    public function param(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataService $dataService,
        CacheInterface $cache,
        AttachmentRepository $attachmentRepository
    ): Response
    {
        $this->modalAttachmentDelete();
        $images = [
            'image'   => $attachmentRepository->getImageDefault(),
            'favicon' => $attachmentRepository->getFavicon(),
        ];
        foreach ($images as $key => $value) {
            if (!is_null($value)) {
                continue;
            }

            $images[$key] = new Attachment();
            $images[$key]->setCode($key);
            $attachmentRepository->add($images[$key]);
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
            $post = $request->request->all($form->getName());
            $eventDispatcher->dispatch(new ConfigurationEntityEvent($post));
        }

        $this->btnInstance()->add(
            'btn-admin-header-export',
            'Exporter',
            [
                'href' => $this->generateUrl('admin_export'),
            ]
        );

        return $this->renderForm(
            'admin/param.html.twig',
            [
                'images' => $images,
                'form'   => $form,
            ]
        );
    }

    #[Route(path: '/profil', name: 'admin_profil', methods: ['GET', 'POST'])]
    public function profil(
        Security $security,
        UserRequestHandler $userRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $userRequestHandler,
            ProfilType::class,
            $security->getUser(),
            'admin/profil.html.twig'
        );
    }

    #[Route(path: '/themes', name: 'admin_themes')]
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

        return $this->renderForm(
            'admin/form.html.twig',
            ['form' => $form]
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_trash')]
    public function trash(
        CsrfTokenManagerInterface $csrfTokenManager,
        Environment $environment,
        TrashService $trashService
    ): Response
    {
        $all = $trashService->all();
        if (0 == (is_countable($all) ? count($all) : 0)) {
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('admin.flashbag.trash.empty')
            );

            return $this->redirectToRoute('admin');
        }

        $globals        = $environment->getGlobals();
        $modal          = $globals['modal'] ?? [];
        $modal['empty'] = true;
        if ($this->isRouteEnable('api_action_emptyall')) {
            $value             = $csrfTokenManager->getToken('emptyall')->getValue();
            $modal['emptyall'] = true;
            $this->btnInstance()->add(
                'btn-admin-header-emptyall',
                'Tout vider',
                [
                    'is'       => 'link-btnadminemptyall',
                    'token'    => $value,
                    'redirect' => $this->generateUrl('admin_trash'),
                    'url'      => $this->generateUrl('api_action_emptyall'),
                ]
            );
        }

        $environment->addGlobal('modal', $modal);
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

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title'        => $this->translator->trans('param.title', [], 'admin.breadcrumb'),
                    'route'        => 'admin_param',
                    'route_params' => [],
                ],
                [
                    'title'        => $this->translator->trans('profil.title', [], 'admin.breadcrumb'),
                    'route'        => 'admin_profil',
                    'route_params' => [],
                ],
                [
                    'title'        => $this->translator->trans('trash.title', [], 'admin.breadcrumb'),
                    'route'        => 'admin_trash',
                    'route_params' => [],
                ],
                [
                    'title'        => $this->translator->trans('oauth.title', [], 'admin.breadcrumb'),
                    'route'        => 'admin_oauth',
                    'route_params' => [],
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
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

    private function setUpload(Request $request, array $images): void
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
            $this->moveFile($file, $path, $filename, $attachment, $old);
        }
    }
}
