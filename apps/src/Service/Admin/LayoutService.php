<?php

namespace Labstag\Service\Admin;

use Exception;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Form\Admin\NewLayoutType;
use Labstag\Repository\Block\CustomRepository;
use Labstag\Repository\LayoutRepository;
use Labstag\Service\AdminService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class LayoutService extends AdminService
{
    public function index(
        array $parameters = []
    ): Response
    {
        return $this->listOrTrash('index', $parameters);
    }

    public function new(
        array $parameters = []
    ): Response
    {
        unset($parameters);
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $post    = $request->request->all('new_layout');

        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['list']) || !isset($routes['edit'])) {
            throw new Exception('Route list not found');
        }

        /** @var CustomRepository $customRepository */
        $customRepository = $this->repositoryService->get(Custom::class);
        $custom           = $customRepository->findOneBy(
            [
                'id' => $post['custom'],
            ]
        );
        if (!$custom instanceof Custom) {
            return $this->redirectToRoute($routes['list']);
        }

        $layout = new Layout();
        $layout->setCustom($custom);
        $layout->setName(Uuid::v1());

        /** @var LayoutRepository $layoutRepository */
        $layoutRepository = $this->repositoryService->get(Layout::class);

        $layoutRepository->save($layout);

        return $this->redirectToRoute($routes['edit'], ['id' => $layout->getId()]);
    }

    public function trash(
        array $parameters = []
    ): Response
    {
        return $this->listOrTrash('trash', $parameters);
    }

    private function listOrTrash(
        string $type,
        array $parameters = []
    ): Response
    {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['popupnew'])) {
            throw new Exception('Route popupnew not found');
        }

        $this->adminBtnService->add(
            'btn-admin-header-new',
            'Nouveau',
            [
                'is'       => 'link-btnadminnewblock',
                'data-url' => $this->router->generate($routes['popupnew']),
            ]
        );
        $form = $this->createForm(
            NewLayoutType::class,
            new Layout(),
            [
                'action' => $this->router->generate($routes['popupnew']),
            ]
        );

        $url = $this->domain->getUrlAdmin();
        /** @var Request $request */
        $request   = $this->requeststack->getCurrentRequest();
        $all       = $request->attributes->all();
        $route     = $all['_route'];
        $routeType = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($routeType);
        $pagination = $this->setPagination($routeType);

        if ('trash' == $routeType && 0 == $pagination->count()) {
            throw new AccessDeniedException();
        }

        $parameters = [
            'newform'    => $form,
            'pagination' => $pagination,
            'actions'    => $url,
        ];
        $parameters = $this->setSearchForms($parameters);

        $templates = $this->getDomain()->getTemplates();

        if (!array_key_exists($type, $templates)) {
            throw new Exception('Template not found');
        }

        return $this->render(
            $templates[$type],
            $parameters
        );
    }
}
