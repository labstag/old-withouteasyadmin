<?php

namespace Labstag\Interfaces;

use Labstag\Entity\Paragraph;

interface EntityParagraphInterface
{
    public function getParagraph(): ?Paragraph;

    public function setParagraph(?Paragraph $paragraph): self;
}
