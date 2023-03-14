<?php

namespace Labstag\Controller\Admin;

use DateTime;
use DOMDocument;
use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Bookmark;
use Labstag\Entity\User;
use Labstag\Form\Admin\Bookmark\ImportType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\BookmarkService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/bookmark', name: 'admin_bookmark_')]
class BookmarkController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function edit(
        ?Bookmark $bookmark
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($bookmark) ? new Bookmark() : $bookmark,
            'admin/bookmark/form.html.twig'
        );
    }

    #[Route(path: '/import', name: 'import', methods: ['GET', 'POST'])]
    public function import(Request $request, Security $security, EnqueueMethod $enqueueMethod): Response
    {
        $domain = $this->getDomainEntity();
        $url    = $domain->getUrlAdmin();
        $this->setBtnList($url);
        $form = $this->createForm(ImportType::class, []);
        $this->adminBtnService->addBtnSave($form->getName(), 'Import');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadFile($form, $security, $enqueueMethod);
        }

        return $this->render(
            'admin/bookmark/import.html.twig',
            ['form' => $form]
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/bookmark/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(Bookmark $bookmark): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $bookmark,
            'admin/bookmark/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Bookmark::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }

    private function uploadFile(
        FormInterface $form,
        Security $security,
        EnqueueMethod $enqueueMethod
    ): void
    {
        $file = $form->get('file')->getData();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $domDocument = new DOMDocument();
        $domDocument->loadHTMLFile($file->getPathname(), LIBXML_NOWARNING | LIBXML_NOERROR);

        $domNodeList = $domDocument->getElementsByTagName('a');
        $dateTime    = new DateTime();
        /** @var User $user */
        $user   = $security->getUser();
        $userId = $user->getId();
        foreach ($domNodeList as $tag) {
            $enqueueMethod->enqueue(
                BookmarkService::class,
                'process',
                [
                    'userid' => $userId,
                    'url'    => $tag->getAttribute('href'),
                    'name'   => $tag->nodeValue,
                    'icon'   => $tag->getAttribute('icon'),
                    'date'   => $dateTime->setTimestamp((int) $tag->getAttribute('add_date')),
                ]
            );
        }
    }
}
