<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Block;
use Labstag\Form\Admin\BlockType;
use Labstag\Form\Admin\NewBlockType;
use Labstag\Form\Admin\Search\BlockType as SearchBlockType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\BlockRequestHandler;
use Labstag\Search\BlockSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/block')]
class BlockController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_block_edit', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $service,
        ?Block $block,
        BlockRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            BlockType::class,
            !is_null($block) ? $block : new Block()
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_block_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_block_index', methods: ['GET'])]
    public function indexOrTrash(): Response
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
        $form   = $this->createForm(NewBlockType::class, $entity);

        return $this->listOrTrash(
            Block::class,
            'admin/block/index.html.twig',
            ['newform' => $form]
        );
    }

    #[Route(path: '/new', name: 'admin_block_new', methods: ['GET', 'POST'])]
    public function new(
        AttachFormService $service,
        ?Block $block,
        BlockRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            BlockType::class,
            !is_null($block) ? $block : new Block()
        );
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

    protected function searchForm(): array
    {
        return [
            'form' => SearchBlockType::class,
            'data' => new BlockSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminTemplace(): array
    {
        return [
            [
                'title' => $this->translator->trans('block.title', [], 'admin.breadcrumb'),
                'route' => 'admin_block_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('block.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_block_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceTrash(): array
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
