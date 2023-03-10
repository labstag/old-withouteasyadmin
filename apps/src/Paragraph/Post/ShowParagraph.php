<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Show;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ShowType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post/show';
    }

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

    public function show(Show $show): ?Response
    {
        /** @var Request $request */
        $request    = $this->requestStack->getCurrentRequest();
        $all        = $request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(Post::class);
        $post                       = $serviceEntityRepositoryLib->findOneBy(
            ['slug' => $slug]
        );

        if (!$post instanceof Post) {
            return null;
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
