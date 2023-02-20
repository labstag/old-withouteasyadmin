<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Html;
use Labstag\Form\Admin\Block\HtmlType;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class HtmlBlock extends BlockLib
{
    public function getEntity(): string
    {
        return Html::class;
    }

    public function getForm(): string
    {
        return HtmlType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('html.name', [], 'block');
    }

    public function getCode($html, $content): string
    {
        unset($html, $content);
        return 'html';
    }

    public function getType(): string
    {
        return 'html';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Html $html, $content): Response
    {
        unset($content);

        return $this->render(
            $this->getTemplateFile($this->getCode($html, $content)),
            ['block' => $html]
        );
    }
}
