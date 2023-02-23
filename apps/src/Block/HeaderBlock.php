<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Header;
use Labstag\Form\Admin\Block\HeaderType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
use Symfony\Component\HttpFoundation\Response;

class HeaderBlock extends BlockLib
{
    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

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

    public function show(Header $header, ?EntityPublicLib $entityPublicLib): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getcode($header, $entityPublicLib)),
            ['block' => $header]
        );
    }
}
