<?php

namespace Labstag\Block;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Block\Breadcrumb;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Lib\BlockLib;
use Labstag\Repository\PageRepository;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class BreadcrumbBlock extends BlockLib
{
    public function __construct(
        TranslatorInterface $translator,
        Environment $environment,
        protected RouterInterface $router,
        protected PageRepository $pageRepository
    )
    {
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

    public function show(Breadcrumb $breadcrumb, $content): Response
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

    /**
     * @return mixed[]
     */
    private function setBreadcrumb($content): array
    {
        $data = [];
        $data = $this->setBreadcrumbPage($data, $content);
        $data = $this->setBreadcrumbArticle($data, $content);
        $data = $this->setBreadcrumbEdito($data, $content);
        $data = $this->setBreadcrumbHistory($data, $content);
        $data = $this->setBreadcrumbChapter($data, $content);

        return array_reverse($data);
    }

    private function setBreadcrumbArticle($data, $content)
    {
        if (!$content instanceof Post) {
            return $data;
        }

        $data[] = [
            'route' => $this->router->generate(
                'front_article',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($data, $page);
    }

    private function setBreadcrumbChapter($data, $content)
    {
        if (!$content instanceof Chapter) {
            return $data;
        }

        $data[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $content->getRefhistory()->getSlug(),
                    'chapter' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];

        return $this->setBreadcrumbHistory($data, $content->getRefhistory());
    }

    private function setBreadcrumbEdito($data, $content)
    {
        if (!$content instanceof Edito) {
            return $data;
        }

        $data[] = [
            'route' => $this->router->generate('front_edito'),
            'title' => $content->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => '']
        );

        return $this->setBreadcrumbPage($data, $page);
    }

    private function setBreadcrumbHistory($data, $content)
    {
        if (!$content instanceof History) {
            return $data;
        }

        $data[] = [
            'route' => $this->router->generate(
                'front_history',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-histoires']
        );

        return $this->setBreadcrumbPage($data, $page);
    }

    private function setBreadcrumbPage($data, $content)
    {
        if (!$content instanceof Page) {
            return $data;
        }

        $data[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];
        if ($content->getParent() instanceof Page) {
            $data = $this->setBreadcrumbPage($data, $content->getParent());
        }

        return $data;
    }
}
