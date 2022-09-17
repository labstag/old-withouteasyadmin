<?php

namespace Labstag\Block;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\FooterType;
use Labstag\Lib\BlockLib;

class FooterBlock extends BlockLib
{
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
        unset($content);

        return $this->render(
            $this->getBlockFile('footer'),
            ['block' => $footer]
        );
    }
}
