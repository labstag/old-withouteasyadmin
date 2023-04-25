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
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\Response;

class TextImageParagraph extends ParagraphLib implements ParagraphInterface
{
    public function getCode(EntityParagraphInterface $entityParagraph): string
    {
        unset($entityParagraph);

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

    public function show(EntityParagraphInterface $entityParagraph): ?Response
    {
        if (!$entityParagraph instanceof TextImage) {
            return null;
        }

        $package    = new Package(new EmptyVersionStrategy());
        $image      = $entityParagraph->getImage();
        $attachment = ($image instanceof Attachment) ? $package->getUrl('/'.$image->getName()) : null;

        return $this->render(
            $this->getTemplateFile($this->getCode($entityParagraph)),
            [
                'paragraph'  => $entityParagraph,
                'attachment' => $attachment,
            ]
        );
    }

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
