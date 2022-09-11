<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\PostLibelle;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostLibelleType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\PostLibelleRepository;

class PostLibelleParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return PostLibelle::class;
    }

    public function getForm()
    {
        return PostLibelleType::class;
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

    public function show(PostLibelle $postlibelle)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $code       = $routeParam['code'] ?? null;
        /** @var PostLibelleRepository $repository */
        $repository = $this->getRepository(EntityPost::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierLibelle($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('postlibelle'),
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
