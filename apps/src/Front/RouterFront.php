<?php

namespace Labstag\Front;

class RouterFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        $all    = $this->request->attributes->all();
        $route  = $all['_route'];
        $params = $all['_route_params'];
        if ('front_article_year' == $route && isset($params['year'])) {
            $breadcrumb[] = [
                'route' => $this->router->generate(
                    $route,
                    $params
                ),
                'title' => $params['year'],
            ];
            $page         = $this->pageRepository->findOneBy(
                ['slug' => 'mes-articles/archive']
            );
            $breadcrumb   = $this->setBreadcrumb($page, $breadcrumb);
        }

        return $breadcrumb;
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
