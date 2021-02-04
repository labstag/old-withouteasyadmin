<?php
namespace Labstag\Service;


class AdminBtnService
{

    protected static $instance = null;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new AdminBtnService();
        }

        return self::$instance;
    }
}
