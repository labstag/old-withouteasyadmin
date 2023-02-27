<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class PostParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post';
    }

    public function getEntity(): string
    {
        return Post::class;
    }

    public function getForm(): string
    {
        return PostType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('post.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'post';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Post $post): Response
    {
        /** @var PostRepository $entityRepository */
        $entityRepository = $this->getRepository(EntityPost::class);
        $posts = $entityRepository->getLimitOffsetResult($entityRepository->findPublier(), 5, 0);

        return $this->render(
            $this->getTemplateFile($this->getCode($post)),
            [
                'posts'     => $posts,
                'paragraph' => $post,
            ]
        );
    }

    /**
     * @return array<class-string<Page>>
     */
    public function useIn(): array
    {
        return [
            Page::class,
        ];
    }
}
