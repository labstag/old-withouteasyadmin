<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Form\Admin\NewLayoutType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\Block\CustomRepository;
use Labstag\Repository\LayoutRepository;
use Labstag\RequestHandler\LayoutRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/layout', name: 'admin_layout_')]
class LayoutController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Layout $layout
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($layout) ? new Layout() : $layout,
            'admin/layout/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(Request $request): Response
    {
        $this->adminBtnService->add(
            'btn-admin-header-new',
            'Nouveau',
            [
                'is'       => 'link-btnadminnewblock',
                'data-url' => $this->router->generate('admin_layout_new'),
            ]
        );
        $form = $this->createForm(
            NewLayoutType::class,
            new Layout(),
            [
                'action' => $this->router->generate('admin_layout_new'),
            ]
        );

        $domain    = $this->getDomainEntity();
        $url       = $domain->getUrlAdmin();
        $all       = $request->attributes->all();
        $route     = $all['_route'];
        $routeType = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($routeType, $domain);
        $pagination = $this->setPagination($routeType, $domain);

        if ('trash' == $routeType && 0 == $pagination->count()) {
            throw new AccessDeniedException();
        }

        $parameters = [
            'newform'    => $form,
            'pagination' => $pagination,
            'actions'    => $url,
        ];
        $parameters = $this->setSearchForms($parameters, $domain);

        return $this->render(
            'admin/layout/index.html.twig',
            $parameters
        );
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        LayoutRepository $layoutRepository,
        LayoutRequestHandler $layoutRequestHandler,
        CustomRepository $customRepository
    ): RedirectResponse
    {
        $post   = $request->request->all('new_layout');
        $custom = $customRepository->findOneBy(
            [
                'id' => $post['custom'],
            ]
        );
        if (!$custom instanceof Custom) {
            return $this->redirectToRoute('admin_layout_index');
        }

        $layout = new Layout();
        $layout->setCustom($custom);
        $layout->setName(Uuid::v1());

        $old = clone $layout;
        $layoutRepository->add($layout);
        $layoutRequestHandler->handle($old, $layout);

        return $this->redirectToRoute('admin_layout_edit', ['id' => $layout->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(Layout $layout): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $layout,
            'admin/layout/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Layout::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
