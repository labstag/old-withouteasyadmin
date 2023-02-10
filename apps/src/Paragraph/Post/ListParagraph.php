<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post\Liste;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ListType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class ListParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Liste::class;
    }

    public function getForm(): string
    {
        return ListType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postlist.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postlist';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Liste $liste): Response
    {
        /** @var PostRepository $entityRepository */
        $entityRepository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/list'),
            [
                'pagination' => $pagination,
                'paragraph'  => $liste,
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
