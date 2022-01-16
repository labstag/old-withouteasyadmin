<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Page;
use Labstag\Lib\TemplatePageLib;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookmarkTemplatePage extends TemplatePageLib
{
    public function category(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublierCategory($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
    }

    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        $slug = $page->getSlug().'/';
        $url  = match ($route) {
            'user' => $slug.'user/'.$params['username'],
            'show' => $slug.$params['slug'],
            'libelle' => $slug.'libelle/'.$params['code'],
            'category' => $slug.'category/'.$params['code'],
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
        return 'bookmark';
    }

    public function index()
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/index.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
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
            case 'category':
                return $this->category($search[1]);
            case 'libelle':
                return $this->libelle($search[1]);
            case 'bookmark':
                if (!empty($search[1])) {
                    $history = $this->bookmarkRepository->findOneBy(['slug' => $search[1]]);
                    if (!$history instanceof Bookmark) {
                        throw $this->createNotFoundException();
                    }

                    return $this->show($history);
                }
                return $this->index();
        }
    }

    public function libelle(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->bookmarkRepository->findPublierLibelle($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/bookmarks/list.html.twig',
            [
                'pagination' => $pagination,
                'libelles'   => $this->libelleRepository->findByBookmark(),
                'categories' => $this->categoryRepository->findByBookmark(),
            ]
        );
    }

    public function show(Bookmark $bookmark)
    {
        return new RedirectResponse($bookmark->getUrl(), 302);
    }

    protected function getCaseRegex(): array
    {
        return [
            '/category\/(.*)/' => 'category',
            '/libelle\/(.*)/'  => 'libelle',
            '/\/(.*)/'         => 'bookmark',
        ];
    }
}
