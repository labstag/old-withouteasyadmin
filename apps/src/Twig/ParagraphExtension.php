<?php

namespace Labstag\Twig;

use Labstag\Entity\Paragraph;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ExtensionLib;
use Labstag\Service\ParagraphService;
use Twig\Environment;

class ParagraphExtension extends ExtensionLib
{
    public function __construct(
        protected Environment $twigEnvironment,
        protected ParagraphService $paragraphService
    )
    {
        parent::__construct($twigEnvironment);
    }

    public function getFiltersFunctions(): array
    {
        return [
            'paragraph_name'  => 'getParagraphName',
            'paragraph_id'    => 'getParagraphId',
            'paragraph_class' => 'getParagraphClass',
        ];
    }

    public function getParagraphClass(ParagraphInterface $entityParagraphLib): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraphLib->getParagraph();
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

    public function getParagraphId(ParagraphInterface $entityParagraphLib): string
    {
        /** @var Paragraph $paragraph */
        $paragraph = $entityParagraphLib->getParagraph();

        return 'paragraph-'.$paragraph->getType().'-'.$paragraph->getId();
    }

    public function getParagraphName(string $code): string
    {
        return $this->paragraphService->getNameByCode($code);
    }
}
