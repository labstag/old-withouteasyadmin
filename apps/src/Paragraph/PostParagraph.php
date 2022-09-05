<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class PostParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Post::class;
    }

    public function getForm()
    {
        return PostType::class;
    }

    public function getName()
    {
        return $this->translator->trans('post.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'post';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Post $post)
    {
        /** @var PostRepository $repository */
        $repository = $this->getRepository(EntityPost::class);
        $posts      = $repository->findPublier();

        return $this->render(
            $this->getParagraphFile('post'),
            [
                'posts'     => $posts,
                'paragraph' => $post,
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
