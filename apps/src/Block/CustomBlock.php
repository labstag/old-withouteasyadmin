<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Custom;
use Labstag\Form\Admin\Block\CustomType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\BlockLib;
use Labstag\Repository\LayoutRepository;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class CustomBlock extends BlockLib
{

    protected ?Request $request;

    public function __construct(
        TranslatorInterface $translator,
        Environment $twigEnvironment,
        protected RequestStack $requestStack,
        protected ParagraphService $paragraphService,
        protected LayoutRepository $layoutRepository
    )
    {
        parent::__construct($translator, $twigEnvironment);
    }

    public function getCode(BlockInterface $entityBlockLib, ?FrontInterface $front): string
    {
        unset($entityBlockLib, $front);

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

    public function show(Custom $custom, ?FrontInterface $front): Response
    {
        $paragraphs = $this->setParagraphs($custom);

        return $this->render(
            $this->getTemplateFile($this->getCode($custom, $front)),
            [
                'paragraphs' => $paragraphs,
                'block'      => $custom,
            ]
        );
    }

    private function setParagraphs(Custom $custom): array
    {
        $all         = $this->requestStack->getCurrentRequest()->attributes->all();
        $route       = $all['_route'];
        $dataLayouts = $this->layoutRepository->findByCustom($custom);
        $layouts     = [];
        foreach ($dataLayouts as $layout) {
            if (!in_array($route, $layout->getUrl())) {
                continue;
            }

            $layouts[] = $layout;
        }

        $paragraphs = [];
        foreach ($layouts as $layout) {
            $paragraphs = $this->getParagraphsArray($this->paragraphService, $layout, $paragraphs);
        }

        return $paragraphs;
    }
}
