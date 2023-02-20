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
    public function getEntity(): string
    {
        return Show::class;
    }

    public function getForm(): string
    {
        return ShowType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postshow.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postshow';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function getCode($show): string
    {
        unset($show);
        return 'post/show';
    }

    public function show(Show $show)
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug = $routeParam['slug'] ?? null;
        /** @var PostRepository $entityRepository */
        $entityRepository = $this->getRepository(Post::class);
        $post = $entityRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$post instanceof Post) {
            return;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($show)),
            [
                'post'      => $post,
                'paragraph' => $show,
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
