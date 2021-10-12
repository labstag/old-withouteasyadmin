<?php

namespace Labstag\Controller\Admin;

use DateTime;
use DOMDocument;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Bookmark;
use Labstag\Form\Admin\Bookmark\ImportType;
use Labstag\Form\Admin\Bookmark\PrincipalType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Queue\EnqueueMethod;
use Labstag\Repository\BookmarkRepository;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Labstag\Service\AttachFormService;
use Labstag\Service\BookmarkService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin/bookmark")
 */
class BookmarkController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_bookmark_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_bookmark_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Bookmark $bookmark,
        BookmarkRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            PrincipalType::class,
            !is_null($bookmark) ? $bookmark : new Bookmark(),
            'admin/bookmark/form.html.twig'
        );
    }

    /**
     * @Route("/import", name="admin_bookmark_import", methods={"GET","POST"})
     */
    public function import(
        Request $request,
        Security $security,
        EnqueueMethod $enqueue
    )
    {
        $this->setBtnList($this->getUrlAdmin());
        $form = $this->createForm(ImportType::class, []);
        $this->btnInstance()->addBtnSave($form->getName(), 'Import');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->uploadFile($form, $security, $enqueue);
        }

        return $this->render(
            'admin/bookmark/import.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/trash",  name="admin_bookmark_trash", methods={"GET"})
     * @Route("/",       name="admin_bookmark_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(BookmarkRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/bookmark/index.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_bookmark_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_bookmark_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Bookmark $bookmark
    ): Response
    {
        return $this->renderShowOrPreview(
            $bookmark,
            'admin/bookmark/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_bookmark_edit',
            'empty'    => 'api_action_empty',
            'import'   => 'admin_bookmark_import',
            'list'     => 'admin_bookmark_index',
            'new'      => 'admin_bookmark_new',
            'preview'  => 'admin_bookmark_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_bookmark_show',
            'trash'    => 'admin_bookmark_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function setBreadcrumbsPageAdminBookmark(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkImport(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.import', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_import',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('bookmark.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('bookmark.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBookmarkTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_bookmark_trash',
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
                'admin_bookmark' => $this->translator->trans('bookmark.title', [], 'admin.header'),
            ]
        );
    }

    private function uploadFile(
        FormInterface $form,
        Security $security,
        EnqueueMethod $enqueue
    )
    {
        $file = $form->get('file')->getData();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $doc = new DOMDocument();
        $doc->loadHTMLFile($file->getPathname(), LIBXML_NOWARNING | LIBXML_NOERROR);
        $tags = $doc->getElementsByTagName('a');
        $date = new DateTime();
        $user = $security->getUser()->getId();
        foreach ($tags as $tag) {
            $enqueue->enqueue(
                BookmarkService::class,
                'process',
                [
                    'userid' => $user,
                    'url'    => $tag->getAttribute('href'),
                    'name'   => $tag->nodeValue,
                    'icon'   => $tag->getAttribute('icon'),
                    'date'   => $date->setTimestamp((int) $tag->getAttribute('add_date')),
                ]
            );
        }
    }
}
