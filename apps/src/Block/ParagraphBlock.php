<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
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

    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

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

    public function show(Paragraph $paragraph, ?EntityPublicLib $entityPublicLib): Response
    {
        $data = $this->setParagraphs($entityPublicLib);

        return $this->render(
            $this->getTemplateFile($this->getCode($paragraph, $entityPublicLib)),
            [
                'paragraphs' => $data,
                'block'      => $paragraph,
            ]
        );
    }

    private function setParagraphs(?EntityPublicLib $entityPublicLib): array
    {
        $paragraphs = [];
        if (is_null($entityPublicLib)) {
            return $paragraphs;
        }

        $methods = get_class_methods($entityPublicLib);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        return $this->getParagraphsArray($this->paragraphService, $entityPublicLib, $paragraphs);
    }
}
