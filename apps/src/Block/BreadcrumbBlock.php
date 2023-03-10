<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\BlockLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\FrontService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class BreadcrumbBlock extends BlockLib
{
    public function __construct(
        TranslatorInterface $translator,
        Environment $twigEnvironment,
        protected FrontService $frontService,
        protected RequestStack $requestStack,
        protected RouterInterface $router,
        protected PageRepository $pageRepository
    )
    {
        parent::__construct($translator, $twigEnvironment);
    }

    public function getCode(BlockInterface $entityBlockLib, ?FrontInterface $front): string
    {
        unset($entityBlockLib, $front);

        return 'breadcrumb';
    }

    public function getEntity(): string
    {
        return Breadcrumb::class;
    }

    public function getForm(): string
    {
        return BreadcrumbType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('breadcrumb.name', [], 'block');
    }

    public function getType(): string
    {
        return 'breadcrumb';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Breadcrumb $breadcrumb, ?FrontInterface $front): ?Response
    {
        $breadcrumbs = $this->frontService->setBreadcrumb($front);
        if ((is_countable($breadcrumbs) ? count($breadcrumbs) : 0) <= 1) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($breadcrumb, $front)),
            [
                'breadcrumbs' => $breadcrumbs,
                'block'       => $breadcrumb,
            ]
        );
    }
}
