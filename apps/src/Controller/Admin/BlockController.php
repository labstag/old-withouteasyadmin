<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Domain\BlockDomain;
use Labstag\Entity\Block;
use Labstag\Form\Admin\NewBlockType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\BlockRepository;
use Labstag\Service\BlockService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/block', name: 'admin_block_')]
class BlockController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        BlockService $blockService,
        Block $block
    ): Response
    {
        $field = $blockService->getEntityField($block);

        return $this->form(
            $this->getDomainEntity(),
            $block,
            'admin/block/form.html.twig',
            ['field' => $field]
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(Request $request): Response
    {
        $region = null;
        $this->adminBtnService->add(
            'btn-admin-header-new',
            'Nouveau',
            [
                'is'       => 'link-btnadminnewblock',
                'data-url' => $this->router->generate('admin_block_new'),
            ]
        );
        $this->adminBtnService->addBtnList(
            'admin_block_move',
            'Move',
        );
        $form = $this->createForm(
            NewBlockType::class,
            new Block(),
            [
                'action' => $this->router->generate('admin_block_new'),
            ]
        );
        /** @var BlockDomain $domain */
        $domain = $this->getDomainEntity();
        $url    = $domain->getUrlAdmin();
        /** @var BlockRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $domain->getRepository();
        $all                        = $request->attributes->all();
        $route                      = $all['_route'];
        $routeType                  = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($routeType, $domain);
        $data  = $serviceEntityRepositoryLib->getDataByRegion();
        $total = 0;
        foreach ($data as $region) {
            $total += is_countable($region) ? count($region) : 0;
        }

        if ('trash' == $routeType && 0 == $total) {
            throw new AccessDeniedException();
        }

        return $this->render(
            'admin/block/index.html.twig',
            [
                'data'    => $data,
                'actions' => $url,
                'newform' => $form,
            ]
        );
    }

    #[Route(path: '/move', name: 'move', methods: ['GET', 'POST'])]
    public function move(
        BlockRepository $blockRepository,
        Request $request
    ): Response
    {
        $currentUrl = $this->generateUrl('admin_block_move');
        if ('POST' == $request->getMethod()) {
            $this->setPositionEntity($request, Block::class);
        }

        $this->adminBtnService->addBtnList(
            'admin_block_index',
            'Liste',
        );
        $this->adminBtnService->add(
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

    #[Route(path: '/new', name: 'new', methods: ['POST'])]
    public function new(
        Request $request,
        ?Block $block,
        BlockRepository $blockRepository
    ): RedirectResponse
    {
        $post  = $request->request->all('new_block');
        $block = new Block();
        if (!is_string($post['region']) || !is_string($post['type'])) {
            throw new Exception('Region or type not found');
        }

        $block->setTitle(Uuid::v1());
        $block->setRegion($post['region']);
        $block->setType($post['type']);

        $blockRepository->add($block);

        return $this->redirectToRoute('admin_block_edit', ['id' => $block->getId()]);
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Block::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
