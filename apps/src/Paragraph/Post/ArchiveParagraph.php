<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Post\Archive;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\ArchiveType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

class ArchiveParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Archive::class;
    }

    public function getForm()
    {
        return ArchiveType::class;
    }

    public function getName()
    {
        return $this->translator->trans('postarchive.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postarchive';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Archive $postarchive)
    {
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $archives   = $repository->findDateArchive();

        return $this->render(
            $this->getParagraphFile('post/archive'),
            [
                'archives'  => $archives,
                'paragraph' => $postarchive,
            ]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
