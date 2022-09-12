<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post\Liste;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class ListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Liste::class;
    }

    public function getForm()
    {
        return ListType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postlist.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postlist';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Liste $postlist)
    {
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/list'),
            [
                'pagination' => $pagination,
                'paragraph'  => $postlist,
            ]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
