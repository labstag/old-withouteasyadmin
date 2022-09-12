<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Libelle;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\LibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class LibelleParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Libelle::class;
    }

    public function getForm()
    {
        return LibelleType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postlibelle.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postlibelle';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Libelle $postlibelle)
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
                'paragraph'  => $postlibelle,
            ]
        );
    }

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }
}
