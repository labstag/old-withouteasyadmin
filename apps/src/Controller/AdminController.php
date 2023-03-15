<?php

namespace Labstag\Controller;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Attachment;
use Labstag\Event\ConfigurationEntityEvent;
use Labstag\Form\Admin\FormType;
use Labstag\Form\Admin\ParamType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\MemoRepository;
use Labstag\Service\DataService;
use Labstag\Service\OauthService;
use Labstag\Service\TrashService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $file    = dirname(__DIR__, 1).'/json/config.json';
        if (!is_file($file)) {
            throw new Exception('File not found');
        }

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

        $metatags = (array) $this->getParameter('metatags');
        $config   = $dataService->getConfigWithMetatags($metatags);
        foreach ($images as $key => $value) {
            $images[$key] = $value;
        }

        $form = $this->createForm(ParamType::class, $config);
        $this->adminBtnService->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->setUpload($request, $images);
            $cache->delete('configuration');
            $post = $request->request->all($form->getName());
            $eventDispatcher->dispatch(new ConfigurationEntityEvent($post));
        }

        $this->adminBtnService->add(
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
                'form'   => $form,
            ]
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

        return $this->render(
            'admin/form.html.twig',
            ['form' => $form]
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_trash')]
    public function trash(
        CsrfTokenManagerInterface $csrfTokenManager,
        Environment $twigEnvironment,
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

        $globals = $twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        if (!isset($modal['empty'])) {
            $modal['empty'] = false;
        }

        $modal['empty'] = true;
        if ($this->isRouteEnable('api_action_emptyall')) {
            $value = $csrfTokenManager->getToken('emptyall')->getValue();

            if (!isset($modal['emptyall'])) {
                $modal['emptyall'] = false;
            }

            $modal['emptyall'] = true;
            $this->adminBtnService->add(
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

        $twigEnvironment->addGlobal('modal', $modal);
        $this->adminBtnService->addViderSelection(
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

    private function setUpload(Request $request, array $images): void
    {
        $all              = $request->files->all();
        $kernelProjectDir = $this->getParameter('kernel.project_dir');
        $fileDirectory    = $this->getParameter('file_directory');
        if (!is_string($kernelProjectDir) || !is_string($fileDirectory)) {
            return;
        }

        $files = $all['param'];
        $paths = [
            'image'   => $fileDirectory,
            'favicon' => $kernelProjectDir.'/public',
        ];
        foreach ($paths as $path) {
            /** @var string $path */
            if (is_dir($path)) {
                continue;
            }

            mkdir($path, 0777, true);
        }

        foreach ($files as $key => $file) {
            if (is_null($file) && !isset($paths[$key])) {
                continue;
            }

            $attachment = $images[$key];
            $filename   = $file->getClientOriginalName();
            $path       = $paths[$key];
            $filename   = ('favicon' == $key) ? 'favicon.ico' : $filename;
            $this->fileService->moveFile(
                $file,
                $path,
                $filename,
                $attachment
            );
        }
    }
}
