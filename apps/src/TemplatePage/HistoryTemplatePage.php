<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\History;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HistoryTemplatePage extends TemplatePageLib
{
    public function archive(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->historyRepository->findPublierArchive($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->historyRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function category(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->historyRepository->findPublierCategory($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->historyRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function libelle(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->historyRepository->findPublierLibelle($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->historyRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function pdf(History $history)
    {
        $this->historyService->process(
            $this->getParameter('file_directory'),
            $history->getId(),
            false
        );

        $filename = $this->historyService->getFilename();
        if (empty($filename)) {
            throw new NotFoundHttpException('Pas de fichier');
        }

        $filename = str_replace(
            $this->getParameter('kernel.project_dir').'/public/',
            '/',
            $filename
        );

        return new RedirectResponse($filename, 302);
    }

    public function show(History $history)
    {
        $this->setMetaOpenGraph(
            $history->getName(),
            $history->getMetaKeywords(),
            $history->getMetaDescription(),
            null
        );

        return $this->render(
            'front/histories/show.html.twig',
            [
                'history'    => $history,
                'archives'   => $this->historyRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function user(string $username)
    {
        $pagination = $this->paginator->paginate(
            $this->historyRepository->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->historyRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }
}
