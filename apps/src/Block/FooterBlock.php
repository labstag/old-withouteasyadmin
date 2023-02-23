<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\FooterType;
use Labstag\Lib\BlockLib;
use Labstag\Lib\EntityBlockLib;
use Labstag\Lib\EntityPublicLib;
use Symfony\Component\HttpFoundation\Response;

class FooterBlock extends BlockLib
{
    public function getCode(EntityBlockLib $entityBlockLib, ?EntityPublicLib $entityPublicLib): string
    {
        unset($entityBlockLib, $entityPublicLib);

        return 'footer';
    }

    public function getEntity(): string
    {
        return Footer::class;
    }

    public function getForm(): string
    {
        return FooterType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('footer.name', [], 'block');
    }

    public function getType(): string
    {
        return 'footer';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(Footer $footer, ?EntityPublicLib $entityPublicLib): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($footer, $entityPublicLib)),
            ['block' => $footer]
        );
    }
}
