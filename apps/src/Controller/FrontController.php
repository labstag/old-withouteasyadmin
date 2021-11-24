<?php

namespace Labstag\Controller;

use Labstag\Entity\Edito;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Labstag\Service\TemplatePageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    /**
     * @Route("/edito", name="edito")
     */
    public function edito(EditoRepository $editoRepository): Response
    {
        // @var Edito $edito
        $edito = $editoRepository->findOnePublier();
        $this->setMetaOpenGraph(
            $edito->getTitle(),
            $edito->getMetaKeywords(),
            $edito->getMetaDescription(),
            $edito->getFond()
        );

        return $this->render(
            'front/edito.html.twig',
            ['edito' => $edito]
        );
    }

    /**
     * @Route("/{slug}", name="front", requirements={"slug"=".+"}, defaults={"slug"=""})
     */
    public function index(
        string $slug,
        TemplatePageService $templatePageService,
        PageRepository $pageRepository,
        EditoRepository $editoRepository,
        Request $request,
        PostRepository $postRepository,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    ): Response
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
        if (isset($page)) {
            list($className, $method) = explode('::', $page->getFunction());
            $class = $templatePageService->getClass($className);
            echo $class->$method();
        }else{
            throw $this->createNotFoundException();
        }

        exit();
        $pagination = $this->paginator->paginate(
            $postRepository->findPublier(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/index.html.twig',
            [
                'edito'      => $editoRepository->findOnePublier(),
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }
}
