<?php

namespace Labstag\Singleton;

class BreadcrumbsSingleton
{

    protected array $data = [];

    protected static $instance;

    protected function __construct()
    {
    }

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

    public static function getInstance(): ?BreadcrumbsSingleton
    {
        if (is_null(self::$instance)) {
            self::$instance = new BreadcrumbsSingleton();
        }

        return self::$instance;
    }

    /**
     * @param mixed[] $breadcrumbs
     */
    public function set(array $breadcrumbs): void
    {
        $this->data = $breadcrumbs;
    }
}
