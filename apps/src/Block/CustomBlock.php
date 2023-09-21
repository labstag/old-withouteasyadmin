<?php

namespace Labstag\Block;

use Exception;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Form\Admin\Block\CustomType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Lib\BlockLib;
use Labstag\Repository\LayoutRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomBlock extends BlockLib implements BlockInterface
{
    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

        return 'custom';
    }

    public function getEntity(): string
    {
        return Custom::class;
    }

    public function getForm(): string
    {
        return CustomType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('custom.name', [], 'block');
    }

    public function getType(): string
    {
        return 'custom';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    private function setParagraphs(Custom $custom): array
    {
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $all     = $request->attributes->all();
        $route   = $all['_route'];
        /** @var LayoutRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Layout::class);
        $dataLayouts   = $repositoryLib->findByCustom($custom);
        $layouts       = [];
        if (!is_iterable($dataLayouts)) {
            throw new Exception('Layouts invalide');
        }

        foreach ($dataLayouts as $layout) {
            /** @var Layout $layout */
            $urls = $layout->getUrl();
            if (!is_iterable($urls) || !in_array($route, $urls)) {
                continue;
            }

            $layouts[] = $layout;
        }

        $paragraphs = [];
        foreach ($layouts as $layout) {
            $paragraphs = $this->getParagraphsArray(
                $this->paragraphService,
                $layout,
                $paragraphs
            );
        }

        return $paragraphs;
    }

    public function context(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): mixed
    {
        if (!$entityBlock instanceof Custom) {
            return null;
        }

        $data     = $this->setParagraphs($entityBlock);
        $redirect = null;
        foreach ($data as $paragraphs) {
            if (!$paragraphs['data'] instanceof RedirectResponse) {
                continue;
            }

            $redirect = $paragraphs['data'];

            break;
        }

        if (!is_null($redirect)) {
            return $redirect;
        }

        return [
            'paragraphs' => $data,
            'block'      => $entityBlock,
        ];
    }
}
