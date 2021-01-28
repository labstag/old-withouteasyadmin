<?php
namespace Labstag\Service;

use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class BreadcrumbsService
{

    protected static $instance = null;

    protected Breadcrumbs $breadcrumbs;

    protected array $data = [];

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new BreadcrumbsService();
        }

        return self::$instance;
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

            $integer++;
        }

        $this->data = $newbreadcrumbs;
    }

    public function add(array $breadcrumbs): void
    {
        foreach ($breadcrumbs as $key => $row) {
            $this->data[$key] = $row;
        }
    }

    public function set(array $breadcrumbs): void
    {
        $this->data = $breadcrumbs;
    }

    public function get(): array
    {
        return $this->data;
    }
}
