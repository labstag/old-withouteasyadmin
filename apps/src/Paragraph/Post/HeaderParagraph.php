<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Header;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\HeaderType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class HeaderParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Header::class;
    }

    public function getForm()
    {
        return HeaderType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postheader.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postheader';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Header $header)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $post       = $repository->findOneBy(
            ['slug' => $slug]
        );

        if (!$post instanceof Post) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('post/header'),
            [
                'post'      => $post,
                'paragraph' => $header,
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
