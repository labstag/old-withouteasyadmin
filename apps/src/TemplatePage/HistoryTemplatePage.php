<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HistoryTemplatePage extends TemplatePageLib
{
    public function chapter($historySlug, $chapterSlug)
    {
        $history = $this->getRepository(History::class)->findOneBy(['slug' => $historySlug]);
        if (!$history instanceof History) {
            throw $this->createNotFoundException();
        }

        $chapters = $history->getchapters();
        $prev     = null;
        $next     = null;
        foreach ($chapters as $i => $row) {
            if ($row->getSlug() == $chapterSlug) {
                $prev    = $chapters[$i - 1] ?? null;
                $next    = $chapters[$i + 1] ?? null;
                $chapter = $row;

                break;
            }
        }

        return $this->render(
            'front/histories/chapter.html.twig',
            [
                'history' => $history,
                'prev'    => $prev,
                'next'    => $next,
                'chapter' => $chapter,
            ]
        );
    }

    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        $slug = $page->getSlug().'/';
        $url  = match ($route) {
            'user' => $slug.'user/'.$params['username'],
            'show' => $slug.$params['slug'],
            'chapter' => $slug.$params['history'].'/'.$params['chapter'],
            'pdf' => $slug.$params['slug'].'.pdf',
            default => $slug,
        };

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

    public function __invoke($matches)
    {
        [
            $case,
            $search,
        ] = $this->getCaseSlug($matches[1]);
        if ('' == $case) {
            throw $this->createNotFoundException();
        }

        switch ($case) {
            case 'list':
                return $this->list();
            case 'user':
                return $this->user($search[1]);
            default:
                if (!empty($search[1])) {
                    if (1 == substr_count($search[1], '/')) {
                        [
                            $historySlug,
                            $chapterSlug,
                        ] = explode('/', (string) $search[1]);

                        return $this->chapter($historySlug, $chapterSlug);
                    }

                    $history = $this->getRepository(History::class)->findOneBy(['slug' => $search[1]]);
                    if (!$history instanceof History) {
                        throw $this->createNotFoundException();
                    }

                    return ('show' == $case) ? $this->show($history) : $this->pdf($history);
                }
                throw $this->createNotFoundException();
        }
    }

    public function list()
    {
        $pagination = $this->paginator->paginate(
            $this->getRepository(History::class)->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            ['pagination' => $pagination]
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
            ['history' => $history]
        );
    }

    public function user(string $username)
    {
        $pagination = $this->paginator->paginate(
            $this->getRepository(History::class)->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/histories/list.html.twig',
            ['pagination' => $pagination]
        );
    }

    protected function getCaseRegex(): array
    {
        return [
            '/user\/(.*)/'   => 'user',
            '/\/(.*).pdf/'   => 'pdf',
            '/\/(.*[^\/])/'  => 'show',
            '/\/(.*)\/(.*)/' => 'chapter',
            '//'             => 'list',
        ];
    }
}
