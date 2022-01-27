<?php

namespace Labstag\Controller;

use Labstag\Entity\Page;
use Labstag\Lib\ControllerLib;
use Labstag\Service\TemplatePageService;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends ControllerLib
{
    #[Route(path: '/{slug}', name: 'front', requirements: ['slug' => '.+'], defaults: ['slug' => ''], priority: -1)]
    public function front(string $slug, TemplatePageService $templatePageService) : mixed
    {
        $slug = trim($slug);
        if ('' == $slug) {
            $page = $this->getRepository(Page::class)->findOneBy(['front' => 1]);
        }
        $search = $slug;
        $find   = 0;
        $page   = null;
        $strlen = strlen($search);
        while (0 == $find || 0 != $strlen) {
            $searchPage = $this->getRepository(Page::class)->findOneBy(['frontslug' => $search]);
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
        $slug = strstr($slug, (string) $page->getSlug());
        return $class->launch($matches);
    }
}
