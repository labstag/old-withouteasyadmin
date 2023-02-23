<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Attachment;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\TextImage;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\TextImageType;
use Labstag\Lib\EntityParagraphLib;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Response;

class TextImageParagraph extends ParagraphLib
{
    public function getCode(EntityParagraphLib $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'textimage';
    }

    public function getEntity(): string
    {
        return TextImage::class;
    }

    public function getForm(): string
    {
        return TextImageType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('textimage.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'textimage';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(TextImage $textimage): Response
    {
        $package = new Package(new EmptyVersionStrategy());
        $attachment = ($textimage->getImage() instanceof Attachment) ? $package->getUrl('/'.$textimage->getImage()->getName()) : null;

        return $this->render(
            $this->getTemplateFile($this->getCode($textimage)),
            [
                'paragraph'  => $textimage,
                'attachment' => $attachment,
            ]
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
