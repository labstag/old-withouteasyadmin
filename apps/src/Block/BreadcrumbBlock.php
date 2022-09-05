<?php

namespace Labstag\Block;

use Labstag\Entity\Block\Breadcrumb;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Post;
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

    public function isShowForm()
    {
        return false;
    }

    public function show(Breadcrumb $breadcrumb, $content)
    {
        $breadcrumbs = $this->setBreadcrumb($content);

        return $this->render(
            $this->getBlockFile('breadcrumb'),
            [
                'breadcrumbs' => $breadcrumbs,
                'block'       => $breadcrumb,
            ]
        );
    }

    private function setBreadcrumb($content)
    {
        $data = [];
        $data = $this->setBreadcrumbArticle($data, $content);
        $data = $this->setBreadcrumbEdito($data, $content);

        return $this->setBreadcrumbHistory($data, $content);
    }

    private function setBreadcrumbArticle($data, $content)
    {
        if (!$content instanceof Post) {
            return $data;
        }

        return $data;
    }

    private function setBreadcrumbEdito($data, $content)
    {
        if (!$content instanceof Edito) {
            return $data;
        }

        return $data;
    }

    private function setBreadcrumbHistory($data, $content)
    {
        if (!$content instanceof History) {
            return $data;
        }

        return $data;
    }
}
