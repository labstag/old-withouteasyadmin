<?php

namespace Labstag\Controller;

use Labstag\Entity\Paragraph;
use Labstag\Service\ParagraphService;

class TwigController
{
    public function paragraph(ParagraphService $service, Paragraph $paragraph)
    {
        return $service->showContent($paragraph);
    }
}
