<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\FooterType;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class FooterBlock extends BlockLib
{
    public function getCode($footer, $content): string
    {
        unset($footer, $content);

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

    public function show(Footer $footer, $content): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($footer, $content)),
            ['block' => $footer]
        );
    }
}
