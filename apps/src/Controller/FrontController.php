<?php

namespace Labstag\Controller;

use Labstag\Entity\Page;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/{slug}", name="front", requirements={"slug"=".+"}, defaults={"slug"=""}, priority=-1)
     */
    public function front(
        string $slug,
        TemplatePageService $templatePageService,
        PageRepository $pageRepository
    ): mixed {
        $slug = trim($slug);
        if ('' == $slug) {
            $page = $pageRepository->findOneBy(['front' => 1]);
        }

        $search = $slug;
        $find   = 0;
        $page   = null;
        $strlen = strlen($search);
        while (0 == $find || 0 != $strlen) {
            $searchPage = $pageRepository->findOneBy(['frontslug' => $search]);
            if ($searchPage instanceof Page) {
                $page = $searchPage;

                break;
            }

            $search = substr($search, 0, -1);
            $strlen = strlen($search);
        }

        if (!isset($page)) {
            throw $this->createNotFoundException();
        }

        $slugFront = $page->getFrontslug();

        preg_match('/'.$slugFront.'(.*)/', $slug, $matches);

        $class = $templatePageService->getClass($page->getFunction());
        if (is_null($class)) {
            throw $this->createNotFoundException();
        }

        $slug = strstr($slug, $page->getSlug());

        return $class->launch($matches);
    }
}
