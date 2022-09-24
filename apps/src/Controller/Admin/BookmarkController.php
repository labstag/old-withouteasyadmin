<?php

namespace Labstag\Controller\Admin;

use DateTime;
use DOMDocument;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Bookmark;
use Labstag\Entity\User;
use Labstag\Form\Admin\Bookmark\ImportType;
use Labstag\Form\Admin\Search\BookmarkType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Queue\EnqueueMethod;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Labstag\Search\BookmarkSearch;
use Labstag\Service\BookmarkService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(path: '/admin/bookmark')]
class BookmarkController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_bookmark_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_bookmark_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Bookmark $bookmark,
        BookmarkRequestHandler $bookmarkRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            is_null($bookmark) ? new Bookmark() : $bookmark,
            'admin/bookmark/form.html.twig'
        );
    }

    #[Route(path: '/import', name: 'admin_bookmark_import', methods: ['GET', 'POST'])]
    public function import(Request $request, Security $security, EnqueueMethod $enqueueMethod): Response
    {
        $domain = $this->getDomainEntity();
        $url    = $domain->getUrlAdmin();
        $this->setBtnList($url);
        $form = $this->createForm(ImportType::class, []);
        $this->btnInstance()->addBtnSave($form->getName(), 'Import');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadFile($form, $security, $enqueueMethod);
        }

        return $this->renderForm(
            'admin/bookmark/import.html.twig',
            ['form' => $form]
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_bookmark_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_bookmark_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            'admin/bookmark/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_bookmark_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_bookmark_preview', methods: ['GET'])]
    public function showOrPreview(Bookmark $bookmark): Response
    {
        return $this->renderShowOrPreview(
            $bookmark,
            'admin/bookmark/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Bookmark::class);
    }

    /**
     * @return array<string, \BookmarkSearch>|array<string, string>
     */
    protected function searchForm(): array
    {
        return [
            'form' => BookmarkType::class,
            'data' => new BookmarkSearch(),
        ];
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
                    'title' => $this->translator->trans('bookmark.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_index',
                ],
                [
                    'title' => $this->translator->trans('bookmark.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_edit',
                ],
                [
                    'title' => $this->translator->trans('bookmark.import', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_import',
                ],
                [
                    'title' => $this->translator->trans('bookmark.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_new',
                ],
                [
                    'title' => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_trash',
                ],
                [
                    'title' => $this->translator->trans('bookmark.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_preview',
                ],
                [
                    'title' => $this->translator->trans('bookmark.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_bookmark_show',
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

        return [
            ...$headers, ...
            [
                'admin_bookmark' => $this->translator->trans('bookmark.title', [], 'admin.header'),
            ],
        ];
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
