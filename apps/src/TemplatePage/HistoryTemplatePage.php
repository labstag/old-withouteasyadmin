<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;

class HistoryTemplatePage extends TemplatePageLib
{
    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        $slug = $page->getSlug().'/';
        switch ($route) {
            case 'user':
                $url = $slug.'user/'.$params['username'];

                break;
            case 'show':
                $url = $slug.$params['slug'];

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
            case 'user':
                return $this->user($search[1]);
            default:
                if (!empty($search[1])) {
                    $history = $this->historyRepository->findOneBy(['slug' => $search[1]]);
                    if (!$history instanceof History) {
                        throw $this->createNotFoundException();
                    }

                    return ('show' == $case) ? $this->show($history) : $this->pdf($history);
                }
                return $this->list();
        }
    }

    public function list()
    {
        $pagination = $this->paginator->paginate(
            $this->historyRepository->findPublier(),
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

        dump($history);

        return $this->render(
            'front/histories/show.html.twig',
            ['history' => $history]
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
            ['pagination' => $pagination]
        );
    }

    protected function getCaseRegex(): array
    {
        return [
            '/user\/(.*)/' => 'user',
            '/\/(.*).pdf/' => 'pdf',
            '/\/(.*)/'     => 'show',
        ];
    }
}
