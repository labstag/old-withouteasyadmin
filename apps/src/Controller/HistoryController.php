<?php

namespace Labstag\Controller;

use Labstag\Entity\History;
use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\PostRepository;
use Labstag\Service\HistoryService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/history")
 */
class HistoryController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="history_archive")
     */
    public function archive(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierArchive($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/category/{code}", name="history_category")
     *
     * @return void
     */
    public function category(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierCategory($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/libelle/{code}", name="history_libelle")
     *
     * @return void
     */
    public function libelle(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierLibelle($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/{slug}.pdf", name="history_pdf")
     */
    public function pdf(History $history, HistoryService $service)
    {
        $service->process(
            $this->getParameter('file_directory'),
            $history->getId(),
            false
        );

        $filename = $service->getFilename();
        if (empty($filename)) {
            throw $this->createNotFoundException('Pas de fichier');
        }

        $filename = str_replace(
            $this->getParameter('kernel.project_dir').'/public/',
            '/',
            $filename
        );

        return $this->redirect($filename);
    }

    /**
     * @Route("/{slug}", name="history_show")
     */
    public function show(
        PostRepository $postRepository,
        Post $post,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->setMetaOpenGraph(
            $post->getTitle(),
            $post->getMetaKeywords(),
            $post->getMetaDescription(),
            $post->getImg()
        );

        return $this->render(
            'front/histories/show.html.twig',
            [
                'post'       => $post,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/user/{username}", name="history_user")
     */
    public function user(
        PostRepository $postRepository,
        Request $request,
        $username,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierUsername($username),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }
}
