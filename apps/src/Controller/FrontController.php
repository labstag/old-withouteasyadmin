<?php

namespace Labstag\Controller;

use Labstag\Entity\Edito;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Service\TemplatePageService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/edito", name="edito", priority=1)
     */
    public function edito(
        TemplatePageService $templatePageService
    ): Response
    {
        $className = 'Labstag\TemplatePage\FrontTemplatePage';
        $method    = 'edito';

        $class = $templatePageService->getClass($className);

        return $class->{$method}();
    }

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
            $page = $pageRepository->findOneBy(['slug' => $slug]);
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

        [
            $className,
            $method,
        ] = explode('::', $page->getFunction());

        $class = $templatePageService->getClass($className);

        return $class->{$method}($matches);
    }

    /**
     * @Route("/", name="front", priority=1)
     */
    public function index(
        TemplatePageService $templatePageService
    ): Response
    {
        $className = 'Labstag\TemplatePage\FrontTemplatePage';
        $method    = 'index';

        $class = $templatePageService->getClass($className);

        return $class->{$method}();
    }
}
