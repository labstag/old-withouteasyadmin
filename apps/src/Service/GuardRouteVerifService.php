<?php
namespace Labstag\Service;

class GuardRouteVerifService
{

    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new GuardRouteVerifService();
        }

        return self::$instance;
    }
}
