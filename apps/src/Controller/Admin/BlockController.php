<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Form\Admin\NewBlockType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\BlockRepository;
use Labstag\RequestHandler\BlockRequestHandler;
use Labstag\Search\BlockSearch;
use Labstag\Service\AttachFormService;
use Labstag\Service\BlockService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route(path: '/admin/block')]
class BlockController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_block_edit', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachService,
        BlockService $blockService,
        Block $block,
        BlockRequestHandler $requestHandler
    ): Response
    {
        $field = $blockService->getEntityField($block);

        return $this->form(
            $attachService,
            $requestHandler,
            BlockType::class,
            !is_null($block) ? $block : new Block(),
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
        BlockRepository $repository
    ): Response
    {
        $this->btnInstance()->add(
            'btn-admin-header-new',
            'Nouveau',
            [
                'is'       => 'link-btnadminnewblock',
                'data-url' => $this->routerInterface->generate('admin_block_new'),
            ]
        );
        $entity = new Block();
        $newform   = $this->createForm(
            NewBlockType::class,
            $entity,
            [
                'action' => $this->routerInterface->generate('admin_block_new'),
            ]
        );
        $url         = $this->getUrlAdmin();
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
        $routeType   = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($repository, $routeType);
        $data = $repository->getDataByRegion();
        $total = 0;
        foreach ($data as $region) {
            $total += count($region);
        }

        if ('trash' == $routeType && 0 == $region) {
            throw new AccessDeniedException();
        }

        return $this->renderForm(
            'admin/block/index.html.twig',
            [
                'data'       => $data,
                'actions'    => $url,
                'newform'    => $newform
            ]
        );
    }

    #[Route(path: '/new', name: 'admin_block_new', methods: ['POST'])]
    public function new(
        Request $request,
        ?Block $block,
        BlockRepository $repository,
        BlockRequestHandler $handler
    )
    {
        $post  = $request->request->all('new_block');
        $block = new Block();
        $old   = clone $block;
        $block->setTitle(Uuid::v1());
        $block->setRegion($post['region']);
        $block->setType($post['type']);
        $repository->add($block);
        $handler->handle($old, $block);

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

    protected function setBreadcrumbsPageAdminBlock(): array
    {
        return [
            [
                'title' => $this->translator->trans('block.title', [], 'admin.breadcrumb'),
                'route' => 'admin_block_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBlockEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('block.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_block_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminBlockTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('block.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_block_trash',
            ],
        ];
    }

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
