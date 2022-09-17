<?php

namespace Labstag\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Form\Admin\NewBlockType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\BlockRepository;
use Labstag\RequestHandler\BlockRequestHandler;
use Labstag\Service\AttachFormService;
use Labstag\Service\BlockService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/block')]
class BlockController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_block_edit', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        BlockService $blockService,
        Block $block,
        BlockRequestHandler $blockRequestHandler
    ): Response
    {
        $field = $blockService->getEntityField($block);

        return $this->form(
            $attachFormService,
            $blockRequestHandler,
            BlockType::class,
            is_null($block) ? new Block() : $block,
            'admin/block/form.html.twig',
            ['field' => $field]
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_block_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_block_index', methods: ['GET'])]
    public function indexOrTrash(
        BlockRepository $blockRepository
    ): Response
    {
        $region = null;
        $this->btnInstance()->add(
            'btn-admin-header-new',
            'Nouveau',
            [
                'is'       => 'link-btnadminnewblock',
                'data-url' => $this->routerInterface->generate('admin_block_new'),
            ]
        );
        $this->btnInstance()->addBtnList(
            'admin_block_move',
            'Move',
        );
        $form   = $this->createForm(
            NewBlockType::class,
            new Block(),
            [
                'action' => $this->routerInterface->generate('admin_block_new'),
            ]
        );
        $url       = $this->getUrlAdmin();
        $request   = $this->requeststack->getCurrentRequest();
        $all       = $request->attributes->all();
        $route     = $all['_route'];
        $routeType = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($blockRepository, $routeType);
        $data  = $blockRepository->getDataByRegion();
        $total = 0;
        foreach ($data as $region) {
            $total += is_countable($region) ? count($region) : 0;
        }

        if ('trash' == $routeType && 0 == $total) {
            throw new AccessDeniedException();
        }

        return $this->renderForm(
            'admin/block/index.html.twig',
            [
                'data'    => $data,
                'actions' => $url,
                'newform' => $form,
            ]
        );
    }

    #[Route(path: '/move', name: 'admin_block_move', methods: ['GET', 'POST'])]
    public function move(
        BlockRepository $blockRepository,
        Request $request
    ): Response
    {
        $currentUrl = $this->generateUrl('admin_block_move');
        if ('POST' == $request->getMethod()) {
            $this->setPositionEntity($request, Block::class);
        }

        $this->btnInstance()->addBtnList(
            'admin_block_index',
            'Liste',
        );
        $this->btnInstance()->add(
            'btn-admin-save-move',
            'Enregistrer',
            [
                'is'   => 'link-btnadminmove',
                'href' => $currentUrl,
            ]
        );

        $data = $blockRepository->getDataByRegion();

        return $this->render(
            'admin/block/move.html.twig',
            ['data' => $data]
        );
    }

    #[Route(path: '/new', name: 'admin_block_new', methods: ['POST'])]
    public function new(
        Request $request,
        ?Block $block,
        BlockRepository $blockRepository,
        BlockRequestHandler $blockRequestHandler
    ): RedirectResponse
    {
        $post  = $request->request->all('new_block');
        $block = new Block();
        $old   = clone $block;
        $block->setTitle(Uuid::v1());
        $block->setRegion($post['region']);
        $block->setType($post['type']);

        $blockRepository->add($block);
        $blockRequestHandler->handle($old, $block);

        return $this->redirectToRoute('admin_block_edit', ['id' => $block->getId()]);
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_block_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_block_index',
            'restore' => 'api_action_restore',
            'trash'   => 'admin_block_trash',
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
                    'title' => $this->translator->trans('block.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_block_index',
                ],
                [
                    'title' => $this->translator->trans('block.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_block_edit',
                ],
                [
                    'title' => $this->translator->trans('block.move', [], 'admin.breadcrumb'),
                    'route' => 'admin_block_move',
                ],
                [
                    'title' => $this->translator->trans('block.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_block_trash',
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

        return array_merge(
            $headers,
            [
                'admin_block' => $this->translator->trans('block.title', [], 'admin.header'),
            ]
        );
    }
}
