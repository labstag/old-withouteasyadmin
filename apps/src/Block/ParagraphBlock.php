<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Lib\BlockLib;
use Labstag\Service\ParagraphService;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ParagraphBlock extends BlockLib
{
    public function __construct(
        TranslatorInterface $translator,
        Environment $twig,
        protected ParagraphService $paragraphService
    )
    {
        parent::__construct($translator, $twig);
    }

    public function getEntity()
    {
        return Paragraph::class;
    }

    public function getForm()
    {
        return ParagraphType::class;
    }

    public function getName()
    {
        return $this->translator->trans('paragraph.name', [], 'block');
    }

    public function getType()
    {
        return 'paragraph';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Paragraph $paragraph, $content)
    {
        $data = $this->setParagraphs($content);

        return $this->render(
            $this->getBlockFile('paragraph'),
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
        if (in_array('getParagraphs', $methods)) {
            $paragraphsArray = $content->getParagraphs();
            foreach ($paragraphsArray as $paragraph) {
                $paragraphs[] = $this->paragraphService->showContent($paragraph);
            }
        }

        return $paragraphs;
    }
}
