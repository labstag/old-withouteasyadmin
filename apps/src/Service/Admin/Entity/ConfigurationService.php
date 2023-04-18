<?php

namespace Labstag\Service\Admin\Entity;

use Exception;
use Labstag\Entity\Attachment;
use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ParamType;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\AttachmentRepository;
use Labstag\Service\Admin\ViewService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationService extends ViewService implements AdminEntityServiceInterface
{
    public function export(): RedirectResponse
    {
        $config = $this->dataService->getConfig();
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

    public function form(): Response
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        /** @var AttachmentRepository $attachmentRepository */
        $attachmentRepository = $this->entityManager->getRepository(Attachment::class);
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
            $attachmentRepository->save($images[$key]);
        }

        $metatags = (array) $this->getParameter('metatags');
        $config   = $this->dataService->getConfigWithMetatags($metatags);
        foreach ($images as $key => $value) {
            $images[$key] = $value;
        }

        $form = $this->createForm(ParamType::class, $config);
        $this->btnService->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->setUpload($request, $images);
            $this->cache->delete('configuration');
        }

        $this->btnService->add(
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

    public function getType(): string
    {
        return Configuration::class;
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
