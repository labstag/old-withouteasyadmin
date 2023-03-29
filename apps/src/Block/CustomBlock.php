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

    public function show(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): ?Response
    {
        if (!$entityBlock instanceof Custom) {
            return null;
        }

        $paragraphs = $this->setParagraphs($entityBlock);

        return $this->render(
            $this->getTemplateFile($this->getCode($entityBlock, $entityFront)),
            [
                'paragraphs' => $paragraphs,
                'block'      => $entityBlock,
            ]
        );
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
}
