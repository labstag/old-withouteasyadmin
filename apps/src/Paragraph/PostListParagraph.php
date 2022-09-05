<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\PostList;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\PostListRepository;

class PostListParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return PostList::class;
    }

    public function getForm()
    {
        return PostListType::class;
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

    public function show(PostList $postlist)
    {
        /** @var PostListRepository $repository */
        $repository = $this->getRepository(EntityPost::class);
        $posts      = $repository->getLimitOffsetResult($repository->findPublier(), 5, 0);

        return $this->render(
            $this->getParagraphFile('postlist'),
            [
                'posts'     => $posts,
                'paragraph' => $postlist,
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
