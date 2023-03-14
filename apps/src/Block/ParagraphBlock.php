<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Lib\BlockLib;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ParagraphBlock extends BlockLib implements BlockInterface
{
    public function __construct(
        TranslatorInterface $translator,
        Environment $twigEnvironment,
        protected ParagraphService $paragraphService
    )
    {
        parent::__construct($translator, $twigEnvironment);
    }

    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

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

    public function show(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): ?Response
    {
        if (!$entityBlock instanceof Paragraph) {
            return null;
        }

        $data = $this->setParagraphs($entityFront);

        return $this->render(
            $this->getTemplateFile($this->getCode($entityBlock, $entityFront)),
            [
                'paragraphs' => $data,
                'block'      => $entityBlock,
            ]
        );
    }

    private function setParagraphs(?EntityFrontInterface $entityFront): array
    {
        $paragraphs = [];
        if (is_null($entityFront)) {
            return $paragraphs;
        }

        $methods = get_class_methods($entityFront);
        if (!in_array('getParagraphs', $methods)) {
            return $paragraphs;
        }

        return $this->getParagraphsArray($this->paragraphService, $entityFront, $paragraphs);
    }
}
