<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Lib\BlockLib;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Component\Routing\RouterInterface;

class BreadcrumbBlock extends BlockLib
{

    public function __construct(
        TranslatorInterface $translator,
        Environment $twig,
        protected RouterInterface $routerInterface
    )
    {
        parent::__construct($translator, $twig);
    }

    public function getEntity()
    {
        return Breadcrumb::class;
    }

    public function getForm()
    {
        return BreadcrumbType::class;
    }

    public function getName()
    {
        return $this->translator->trans('breadcrumb.name', [], 'block');
    }

    public function getType()
    {
        return 'breadcrumb';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Breadcrumb $breadcrumb, $content)
    {
        $breadcrumbs = $this->setBreadcrumb($content);

        return $this->render(
            $this->getBlockFile('breadcrumb'),
            [
                'breadcrumbs' => $breadcrumbs,
                'block'       => $breadcrumb,
            ]
        );
    }

    private function setBreadcrumb($content)
    {
        $data = [];
        $data = $this->setBreadcrumbPage($data, $content);
        $data = $this->setBreadcrumbArticle($data, $content);
        $data = $this->setBreadcrumbEdito($data, $content);
        $data = $this->setBreadcrumbHistory($data, $content);
        $data = $this->setBreadcrumbChapter($data, $content);
        $data = array_reverse($data);

        return $data;
    }

    private function setBreadcrumbPage($data, $content)
    {
        if (!$content instanceof Page) {
            return $data;
        }

        return $data;
    }

    private function setBreadcrumbArticle($data, $content)
    {
        if (!$content instanceof Post) {
            return $data;
        }

        $data[] = [
            'route' => $this->routerInterface->generate(
                'front_article',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getTitle()
        ];

        return $data;
    }

    private function setBreadcrumbEdito($data, $content)
    {
        if (!$content instanceof Edito) {
            return $data;
        }

        $data[] = [
            'route' => $this->routerInterface->generate('front_edito'),
            'title' => $content->getTitle()
        ];

        return $data;
    }

    private function setBreadcrumbHistory($data, $content)
    {
        if (!$content instanceof History) {
            return $data;
        }

        $data[] = [
            'route' => $this->routerInterface->generate(
                'front_history',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName()
        ];

        return $data;
    }

    private function setBreadcrumbChapter($data, $content)
    {
        if (!$content instanceof Chapter) {
            return $data;
        }

        $data[] = [
            'route' => $this->routerInterface->generate(
                'front_history_chapter',
                [
                    'history' => $content->getRefhistory()->getSlug(),
                    'chapter' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName()
        ];

        $data = $this->setBreadcrumbHistory($data, $content->getRefhistory());

        return $data;
    }
}
