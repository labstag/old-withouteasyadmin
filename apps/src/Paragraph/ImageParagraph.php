<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Attachment;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Memo;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Image;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\ImageType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Response;

class ImageParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'image';
    }

    public function getEntity(): string
    {
        return Image::class;
    }

    public function getForm(): string
    {
        return ImageType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('image.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'image';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Image $image): Response
    {
        $package = new Package(new EmptyVersionStrategy());
        $attachment = ($image->getImage() instanceof Attachment) ? $package->getUrl('/'.$image->getImage()->getName()) : null;

        return $this->render(
            $this->getTemplateFile($this->getCode($image)),
            [
                'paragraph'  => $image,
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
