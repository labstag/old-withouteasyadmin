<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post;
use Labstag\Form\Admin\Paragraph\PostType;
use Labstag\Lib\ParagraphLib;

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

    public function show(Post $post)
    {
        return $this->render(
            $this->getParagraphFile('post'),
            ['paragraph' => $post]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
