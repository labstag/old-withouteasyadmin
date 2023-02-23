<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\FrontService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class BreadcrumbBlock extends BlockLib
{

    protected ?Request $request;

    public function __construct(
        TranslatorInterface $translator,
        Environment $twigEnvironment,
        protected FrontService $frontService,
        protected RequestStack $requestStack,
        protected RouterInterface $router,
        protected PageRepository $pageRepository
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        parent::__construct($translator, $twigEnvironment);
    }

    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

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

    public function show(Breadcrumb $breadcrumb, ?EntityPublicLib $entityPublicLib): ?Response
    {
        $breadcrumbs = $this->frontService->setBreadcrumb($entityPublicLib);
        if ((is_countable($breadcrumbs) ? count($breadcrumbs) : 0) <= 1) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($breadcrumb, $entityPublicLib)),
            [
                'breadcrumbs' => $breadcrumbs,
                'block'       => $breadcrumb,
            ]
        );
    }
}
