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
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable);
        }

        return $filters;
    }

    public function getFiltersFunctions(): array
    {
        return [
            'paragraph_name'  => 'getParagraphName',
            'paragraph_id'    => 'getParagraphId',
            'paragraph_class' => 'getParagraphClass',
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable);
        }

        return $functions;
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
