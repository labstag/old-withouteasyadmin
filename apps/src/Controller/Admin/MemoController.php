<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MemoRepository;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\Search\MemoSearch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/memo')]
class MemoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_memo_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Memo $memo,
        MemoRequestHandler $memoRequestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            is_null($memo) ? new Memo() : $memo,
            'admin/memo/form.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_memo_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_memo_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            'admin/memo/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_memo_new', methods: ['GET', 'POST'])]
    public function new(
        MemoRepository $memoRepository,
        MemoRequestHandler $memoRequestHandler,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();

        $memo = new Memo();
        $memo->setTitle(Uuid::v1());
        $memo->setRefuser($user);

        $old = clone $memo;
        $memoRepository->add($memo);
        $memoRequestHandler->handle($old, $memo);

        return $this->redirectToRoute('admin_memo_edit', ['id' => $memo->getId()]);
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_memo_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_memo_preview', methods: ['GET'])]
    public function showOrPreview(Memo $memo): Response
    {
        return $this->renderShowOrPreview(
            $memo,
            'admin/memo/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Memo::class);
    }

    /**
     * @return array<string, \MemoSearch>|array<string, class-string<\Labstag\Form\Admin\Search\MemoType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchMemoType::class,
            'data' => new MemoSearch(),
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
                    'title' => $this->translator->trans('memo.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_index',
                ],
                [
                    'title' => $this->translator->trans('memo.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_edit',
                ],
                [
                    'title' => $this->translator->trans('memo.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_new',
                ],
                [
                    'title' => $this->translator->trans('memo.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_trash',
                ],
                [
                    'title' => $this->translator->trans('memo.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_preview',
                ],
                [
                    'title' => $this->translator->trans('memo.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_memo_show',
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
                'admin_memo' => $this->translator->trans('memo.title', [], 'admin.header'),
            ],
        ];
    }
}
