<?php

namespace Labstag\Service\Gestion\Entity;

use DateTime;
use Exception;
use Labstag\Entity\Edito;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\EditoRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class EditoService extends ViewService implements AdminEntityServiceInterface
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

        $edito = new Edito();
        $edito->setPublished(new DateTime());
        $edito->setTitle(Uuid::v1());
        $edito->setRefuser($user);

        /** @var EditoRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Edito::class);
        $repositoryLib->save($edito);

        return $this->redirectToRoute($routes['edit'], ['id' => $edito->getId()]);
    }

    public function getType(): string
    {
        return Edito::class;
    }
}
