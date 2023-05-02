<?php

namespace Labstag\Twig;

use Labstag\Entity\Paragraph;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Lib\ExtensionLib;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ParagraphExtension extends ExtensionLib
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('paragraph_name', [$this, 'getParagraphName']),
            new TwigFilter('paragraph_id', [$this, 'getParagraphId']),
            new TwigFilter('paragraph_class', [$this, 'getParagraphClass']),
        ];
    }

    public function getParagraphClass(EntityParagraphInterface $entityParagraph): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraph->getParagraph();
        $dataClass = [
            'paragraph-'.$paragraph->getType(),
        ];

        $code = $paragraph->getBackground();
        if (!empty($code)) {
            $dataClass[] = 'm--background-'.$code;
        }

        $code = $paragraph->getColor();
        if (!empty($code)) {
            $dataClass[] = 'm--theme-'.$code;
        }

        return implode(' ', $dataClass);
    }

    public function getParagraphId(EntityParagraphInterface $entityParagraph): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraph->getParagraph();

        return 'paragraph-'.$paragraph->getType().'-'.$paragraph->getId();
    }

    public function getParagraphName(string $code): string
    {
        return $this->paragraphService->getNameByCode($code);
    }
}
