<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Html;
use Labstag\Form\Admin\Block\HtmlType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
use Symfony\Component\HttpFoundation\Response;

class HtmlBlock extends BlockLib
{
    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

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

    public function show(Html $html, ?EntityPublicLib $entityPublicLib): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($html, $entityPublicLib)),
            ['block' => $html]
        );
    }
}
