<?php

namespace Labstag\Controller;

use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/{slug}", name="front", requirements={"slug"=".+"}, defaults={"slug"=""})
     */
    public function essai(
        string $slug,
        TemplatePageService $templatePageService,
        PageRepository $pageRepository
    ): mixed
    {
        $slug = trim($slug);
        if ('' == $slug) {
            $page    = $pageRepository->findOneBy(['front' => 1]);
            $matches = [];

            if (!isset($page)) {
                throw $this->createNotFoundException();
            }

            $class = $templatePageService->getClass($page->getFunction());
            $slug  = strstr($slug, $page->getSlug());

            return $class->launch($matches, $slug);
        }

        $pages = $pageRepository->findAll();
        foreach ($pages as $row) {
            $slugReg = $row->getSlug();
            preg_match('/'.$slugReg.'/', $slug, $matches);
            if (count($matches) > 0) {
                $page = $row;

                break;
            }
        }

        if (!isset($page)) {
            throw $this->createNotFoundException();
        }

        $class = $templatePageService->getClass($page->getFunction());
        $slug  = strstr($slug, $page->getSlug());

        return $class->launch($matches, $slug);
    }
}
