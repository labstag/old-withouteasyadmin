<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Flashbag;
use Labstag\Form\Admin\Block\FlashbagType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
use Symfony\Component\HttpFoundation\Response;

class FlashbagBlock extends BlockLib
{
    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

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

    public function show(Flashbag $flashbag, ?EntityPublicLib $entityPublicLib): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($flashbag, $entityPublicLib)),
            ['block' => $flashbag]
        );
    }
}
