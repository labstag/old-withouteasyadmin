<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Lib\BlockLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\FrontService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class BreadcrumbBlock extends BlockLib
{

    protected ?Request $request;

    public function __construct(
        TranslatorInterface $translator,
        Environment $environment,
        protected FrontService $frontService,
        protected RequestStack $requestStack,
        protected RouterInterface $router,
        protected PageRepository $pageRepository
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        parent::__construct($translator, $environment);
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

    public function show(Breadcrumb $breadcrumb, $content)
    {
        $breadcrumbs = $this->frontService->setBreadcrumb($content);
        if ((is_countable($breadcrumbs) ? count($breadcrumbs) : 0) <= 1) {
            return;
        }

        return $this->render(
            $this->getBlockFile('breadcrumb'),
            [
                'breadcrumbs' => $breadcrumbs,
                'block'       => $breadcrumb,
            ]
        );
    }
}
