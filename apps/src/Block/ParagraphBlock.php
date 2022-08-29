<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Paragraph;
use Labstag\Form\Admin\Block\ParagraphType;
use Labstag\Lib\BlockLib;

class ParagraphBlock extends BlockLib
{
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

    public function show(Paragraph $paragraph, $content)
    {
        return $this->render(
            $this->getBlockFile('paragraph'),
            [
                'block'   => $paragraph,
                'content' => $content,
            ]
        );
    }
}
