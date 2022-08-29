<?php

namespace Labstag\Controller;

use Labstag\Entity\Block;
use Labstag\Entity\Paragraph;
use Labstag\Service\BlockService;
use Labstag\Service\ParagraphService;

class TwigController
{
    public function block(BlockService $service, Block $block)
    {
        return $service->showContent($block);
    }

    public function paragraph(ParagraphService $service, Paragraph $paragraph)
    {
        return $service->showContent($paragraph);
    }
}
