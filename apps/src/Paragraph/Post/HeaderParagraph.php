<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Header;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\HeaderType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class HeaderParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post/header';
    }

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

    public function show(Header $header): ?Response
    {
        $all        = $this->request->attributes->all();
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
            $this->getTemplateFile($this->getCode($header)),
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
