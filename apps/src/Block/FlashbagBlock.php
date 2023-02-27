<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Flashbag;
use Labstag\Form\Admin\Block\FlashbagType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class FlashbagBlock extends BlockLib
{
    public function getCode(BlockInterface $entityBlockLib, ?FrontInterface $front): string
    {
        unset($entityBlockLib, $front);

        return 'flashbag';
    }

    public function getEntity(): string
    {
        return Flashbag::class;
    }

    public function getForm(): string
    {
        return FlashbagType::class;
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

    public function show(Flashbag $flashbag, ?FrontInterface $front): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($flashbag, $front)),
            ['block' => $flashbag]
        );
    }
}
