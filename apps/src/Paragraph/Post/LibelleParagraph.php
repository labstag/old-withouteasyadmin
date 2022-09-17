<?php

namespace Labstag\Paragraph\Post;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Libelle;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\LibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class LibelleParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Libelle::class;
    }

    public function getForm(): string
    {
        return LibelleType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postlibelle.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postlibelle';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Libelle $libelle): Response
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierLibelle($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/libelle'),
            [
                'pagination' => $pagination,
                'paragraph'  => $libelle,
            ]
        );
    }

    /**
     * @return array<class-string<Layout>>
     */
    public function useIn(): array
    {
        return [
            Layout::class,
        ];
    }
}
