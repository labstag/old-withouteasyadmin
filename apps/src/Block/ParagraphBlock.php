<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
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

    public function getCode($paragraph, $content): string
    {
        unset($paragraph, $content);

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

    public function show(Paragraph $paragraph, $content): Response
    {
        $data = $this->setParagraphs($content);

        return $this->render(
            $this->getTemplateFile($this->getCode($paragraph, $content)),
            [
                'paragraphs' => $data,
                'block'      => $paragraph,
            ]
        );
    }

    private function setParagraphs($content)
    {
        $paragraphs = [];
        if (is_null($content)) {
            return $paragraphs;
        }

        $methods = get_class_methods($content);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        return $this->getParagraphsArray($this->paragraphService, $content, $paragraphs);
    }
}
