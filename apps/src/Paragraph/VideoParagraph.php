<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Video;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\VideoType;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\HttpFoundation\Response;

class VideoParagraph extends ParagraphLib
{
    public function getEntity(): string
    {
        return Video::class;
    }

    public function getForm(): string
    {
        return VideoType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('video.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'video';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Video $video): Response
    {
        return $this->render(
            $this->getParagraphFile('video'),
            ['paragraph' => $video]
        );
    }

    /**
     * @return class-string[]
     */
    public function useIn(): array
    {
        return [
            Chapter::class,
            Edito::class,
            History::class,
            Layout::class,
            Memo::class,
            Page::class,
            Post::class,
        ];
    }
}
