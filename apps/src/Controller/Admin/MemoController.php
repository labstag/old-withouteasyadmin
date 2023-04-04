<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\MemoRepository;
use Labstag\Service\AdminService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/memo', name: 'admin_memo_')]
class MemoController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Memo $memo
    ): Response
    {
        return $this->setAdmin()->edit($memo);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        MemoRepository $memoRepository,
        Security $security
    ): RedirectResponse
    {
        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('admin_memo_index');
        }

        $memo = new Memo();
        $memo->setTitle(Uuid::v1());
        $memo->setRefuser($user);

        $memoRepository->save($memo);

        return $this->redirectToRoute('admin_memo_edit', ['id' => $memo->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Memo $memo): Response
    {
        return $this->setAdmin()->preview($memo);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Memo $memo): Response
    {
        return $this->setAdmin()->show($memo);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Memo::class);

        return $this->adminService;
    }
}
