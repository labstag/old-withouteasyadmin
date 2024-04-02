<?php

namespace Labstag\Service\Gestion\Entity;

use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;

class TrashService extends ViewService implements AdminEntityServiceInterface
{
    public function getType(): string
    {
        return 'trash';
    }

    public function list(): Response
    {
        $all = $this->trashService->all();
        if (0 == (is_countable($all) ? count($all) : 0)) {
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('admin.flashbag.trash.empty')
            );

            return $this->redirectToRoute('admin');
        }

        $globals = $this->twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        if (!isset($modal['empty'])) {
            $modal['empty'] = false;
        }

        $modal['empty'] = true;
        if ($this->isRouteEnable('api_action_emptyall')) {
            $value = $this->csrfTokenManager->getToken('emptyall')->getValue();

            if (!isset($modal['emptyall'])) {
                $modal['emptyall'] = false;
            }

            $modal['emptyall'] = true;
            $this->btnService->add(
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

        $this->twigEnvironment->addGlobal('modal', $modal);
        $this->btnService->addViderSelection(
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
}
