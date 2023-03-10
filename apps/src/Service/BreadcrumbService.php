<?php

namespace Labstag\Service;

class BreadcrumbService
{

    protected array $data = [];

    public function add(string $title, string $route): void
    {
        $this->data[] = [
            'title' => $title,
            'route' => $route,
        ];
    }

    public function addPosition(array $breadcrumbs, int $position): void
    {
        $newbreadcrumbs = [];
        $integer        = 0;
        foreach ($this->data as $key => $row) {
            $newbreadcrumbs[$key] = $row;
            if ($position === $integer) {
                foreach ($breadcrumbs as $newKey => $newRow) {
                    $newbreadcrumbs[$newKey] = $newRow;
                }
            }

            ++$integer;
        }

        $this->data = $newbreadcrumbs;
    }

    /**
     * @return mixed[]
     */
    public function get(): array
    {
        return $this->data;
    }

    /**
     * @param mixed[] $breadcrumbs
     */
    public function set(array $breadcrumbs): void
    {
        $this->data = $breadcrumbs;
    }
}
