<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\BlockLib;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ParagraphBlock extends BlockLib
{
    public function __construct(
        TranslatorInterface $translator,
        Environment $twigEnvironment,
        protected ParagraphService $paragraphService
    )
    {
        parent::__construct($translator, $twigEnvironment);
    }

    public function getCode(BlockInterface $entityBlockLib, ?FrontInterface $front): string
    {
        unset($entityBlockLib, $front);

        return 'paragraph';
    }

    public function getEntity(): string
    {
        return Paragraph::class;
    }

    public function getForm(): string
    {
        return ParagraphType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('paragraph.name', [], 'block');
    }

    public function getType(): string
    {
        return 'paragraph';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Paragraph $paragraph, ?FrontInterface $front): Response
    {
        $data = $this->setParagraphs($front);

        return $this->render(
            $this->getTemplateFile($this->getCode($paragraph, $front)),
            [
                'paragraphs' => $data,
                'block'      => $paragraph,
            ]
        );
    }

    private function setParagraphs(?FrontInterface $front): array
    {
        $paragraphs = [];
        if (is_null($front)) {
            return $paragraphs;
        }

        $methods = get_class_methods($front);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        return $this->getParagraphsArray($this->paragraphService, $front, $paragraphs);
    }
}
