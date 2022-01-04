<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        unset($params);
        $slug = $page->getSlug().'/';
        switch ($route) {
            case 'user':
                $url = $slug;

                break;
            case 'show':
                $url = $slug;

                break;
            case 'libelle':
                $url = $slug;

                break;
            case 'category':
                $url = $slug;

                break;
            case 'archive':
                $url = $slug;

                break;
            default:
                $url = $slug;
        }

        return $this->router->generate(
            'front',
            ['slug' => $url],
            $relative
        );
    }

    public function getId(): string
    {
        return 'history';
    }

    public function launch($matches)
    {
        [
            $case,
            $search,
        ] = $this->getCaseSlug($matches[1]);
        if ('' == $case) {
            throw $this->createNotFoundException();
        }

        switch ($case) {
            case 'archive':
                return $this->archive($search[1]);
            case 'category':
                return $this->category($search[1]);
            case 'libelle':
                return $this->libelle($search[1]);
            case 'user':
                return $this->user($search[1]);
            default:
                $history = $this->historyRepository->findOneBy(['slug' => $search[1]]);
                if (!$history instanceof History) {
                    throw $this->createNotFoundException();
                }
                return ('show' == $case) ? $this->show($history) : $this->pdf($history);
        }
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
            throw $this->createNotFoundException('Pas de fichier');
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

    protected function getCaseRegex(): array
    {
        return [
            '/archive\/(.*)/'  => 'archive',
            '/category\/(.*)/' => 'category',
            '/libelle\/(.*)/'  => 'libelle',
            '/user\/(.*)/'     => 'user',
            '/\/(.*)/'         => 'show',
            '/\/(.*).pdf/'     => 'pdf',
        ];
    }
}
