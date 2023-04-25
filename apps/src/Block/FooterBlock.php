<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Footer;
use Labstag\Form\Admin\Block\FooterType;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityBlockInterface;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Lib\BlockLib;
use Symfony\Component\HttpFoundation\Response;

class FooterBlock extends BlockLib implements BlockInterface
{
    public function getCode(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string
    {
        unset($entityBlock, $entityFront);

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

    public function show(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): ?Response
    {
        if (!$entityBlock instanceof Footer) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($entityBlock, $entityFront)),
            ['block' => $entityBlock]
        );
    }
}
