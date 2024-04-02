<?php

namespace Labstag\Service\Gestion\Entity;

use Exception;
use Labstag\Entity\Memo;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\MemoRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class MemoService extends ViewService implements AdminEntityServiceInterface
{
    public function add(
        Security $security
    ): RedirectResponse
    {
        $user   = $security->getUser();
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['edit']) || !isset($routes['list'])) {
            throw new Exception('Route edit not found');
        }

        if (is_null($user)) {
            return $this->redirectToRoute($routes['list']);
        }

        $memo = new Memo();
        $memo->setTitle(Uuid::v1());
        $memo->setRefuser($user);

        /** @var MemoRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Memo::class);
        $repositoryLib->save($memo);

        return $this->redirectToRoute($routes['edit'], ['id' => $memo->getId()]);
    }

    public function getType(): string
    {
        return Memo::class;
    }
}
