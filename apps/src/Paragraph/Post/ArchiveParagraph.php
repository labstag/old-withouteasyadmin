<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post\Archive;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ArchiveType;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchiveParagraph extends ParagraphLib implements ParagraphInterface
{
    public function getCode(EntityParagraphInterface $entityParagraph): string
    {
        unset($entityParagraph);

        return 'post/archive';
    }

    public function getEntity(): string
    {
        return Archive::class;
    }

    public function getForm(): string
    {
        return ArchiveType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postarchive.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postarchive';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(EntityParagraphInterface $entityParagraph): ?Response
    {
        /** @var PostRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Post::class);
        $archives      = $repositoryLib->findDateArchive();
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();
        $page    = $request->query->getInt('page', 1);
        if (1 != $page) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($entityParagraph)),
            [
                'archives'  => $archives,
                'paragraph' => $entityParagraph,
            ]
        );
    }

    public function useIn(): array
    {
        return [
            Page::class,
        ];
    }
}
