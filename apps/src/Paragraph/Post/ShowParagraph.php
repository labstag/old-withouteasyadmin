<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Show;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ShowType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class ShowParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Show::class;
    }

    public function getForm()
    {
        return ShowType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postshow.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postshow';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Show $postshow)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $post       = $repository->findOneBy(
            ['slug' => $slug]
        );
        dump($post);

        if (!$post instanceof Post) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('post/show'),
            [
                'post'      => $post,
                'paragraph' => $postshow,
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
