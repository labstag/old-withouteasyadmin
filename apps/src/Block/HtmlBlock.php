<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Html;
use Labstag\Form\Admin\Block\HtmlType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class HtmlBlock extends BlockLib
{
    public function getCode(BlockInterface $entityBlockLib, ?FrontInterface $front): string
    {
        unset($entityBlockLib, $front);

        return 'html';
    }

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

    public function getType(): string
    {
        return 'html';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Html $html, ?FrontInterface $front): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($html, $front)),
            ['block' => $html]
        );
    }
}
