<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\MemoRepository;
use Labstag\RequestHandler\MemoRequestHandler;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/memo')]
class MemoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_memo_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Memo $memo
    ): Response {
        return $this->form(
            $this->getDomainEntity(),
            is_null($memo) ? new Memo() : $memo,
            'admin/memo/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_memo_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_memo_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/memo/index.html.twig',
        );
    }

    #[Route(path: '/new', name: 'admin_memo_new', methods: ['GET', 'POST'])]
    public function new(
        MemoRepository $memoRepository,
        MemoRequestHandler $memoRequestHandler,
        Security $security
    ): RedirectResponse {
        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('admin_memo_index');
        }

        $memo = new Memo();
        $memo->setTitle(Uuid::v1());
        $memo->setRefuser($user);

        $old = clone $memo;
        $memoRepository->add($memo);
        $memoRequestHandler->handle($old, $memo);

        return $this->redirectToRoute('admin_memo_edit', ['id' => $memo->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_memo_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_memo_preview', methods: ['GET'])]
    public function showOrPreview(Memo $memo): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $memo,
            'admin/memo/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Memo::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
