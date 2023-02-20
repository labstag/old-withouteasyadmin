<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Header;
use Labstag\Form\Admin\Block\HeaderType;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class HeaderBlock extends BlockLib
{
    public function getCode($header, $content): string
    {
        unset($header, $content);

        return 'header';
    }

    public function getEntity(): string
    {
        return Header::class;
    }

    public function getForm(): string
    {
        return HeaderType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('header.name', [], 'block');
    }

    public function getType(): string
    {
        return 'header';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Header $header, $content): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getcode($header, $content)),
            ['block' => $header]
        );
    }
}
