<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Flashbag;
use Labstag\Form\Admin\Block\FlashbagType;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class FlashbagBlock extends BlockLib
{
    public function getEntity(): string
    {
        return Flashbag::class;
    }

    public function getForm(): string
    {
        return FlashbagType::class;
    }

    public function getCode($flashbag, $content): string
    {
        unset($flashbag, $content);
        return 'flashbag';
    }

    public function getName(): string
    {
        return $this->translator->trans('flashbag.name', [], 'block');
    }

    public function getType(): string
    {
        return 'flashbag';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Flashbag $flashbag, $content): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($flashbag, $content)),
            ['block' => $flashbag]
        );
    }
}
