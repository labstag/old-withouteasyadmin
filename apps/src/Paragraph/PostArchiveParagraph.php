<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\PostArchive;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostArchiveType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\PostArchiveRepository;

class PostArchiveParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return PostArchive::class;
    }

    public function getForm()
    {
        return PostArchiveType::class;
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

    public function show(PostArchive $postarchive)
    {
        /** @var PostArchiveRepository $repository */
        $repository = $this->getRepository(EntityPost::class);
        $posts      = $repository->getLimitOffsetResult($repository->findPublier(), 5, 0);

        return $this->render(
            $this->getParagraphFile('postarchive'),
            [
                'posts'     => $posts,
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
