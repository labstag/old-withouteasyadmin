<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Form\Admin\Block\BreadcrumbType;
use Labstag\Lib\BlockLib;

class BreadcrumbBlock extends BlockLib
{
    public function getEntity()
    {
        return Breadcrumb::class;
    }

    public function getForm()
    {
        return BreadcrumbType::class;
    }

    public function getName()
    {
        return $this->translator->trans('breadcrumb.name', [], 'block');
    }

    public function getType()
    {
        return 'breadcrumb';
    }

    public function show(Breadcrumb $breadcrumb, $content)
    {
        return $this->render(
            $this->getBlockFile('breadcrumb'),
            [
                'block'   => $breadcrumb,
                'content' => $content,
            ]
        );
    }
}
