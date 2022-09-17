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
    public function getEntity(): string
    {
        return Header::class;
    }

    public function getForm(): string
    {
        return HeaderType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postheader.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postheader';
    }

    public function isShowForm(): bool
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
