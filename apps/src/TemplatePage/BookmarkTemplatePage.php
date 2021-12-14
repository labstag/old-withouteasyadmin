<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Bookmark;
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
                $category = $this->categoryRepository->findOneBy(['slug' => $search[1]]);

                return $this->category($category);
            case 'libelle':
                $libelle = $this->libelleRepository->findOneBy(['slug' => $search[1]]);

                return $this->libelle($libelle);
            case 'post':
                if (!empty($search[1])) {
                    $post = $this->historyRepository->findOneBy(['slug' => $search[1]]);

                    return $this->show($post);
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

    protected function getCaseRegex()
    {
        return [
            '/category\/(.*)/' => 'category',
            '/libelle\/(.*)/'  => 'libelle',
            '/\/(.*)/'         => 'bookmark',
        ];
    }
}
