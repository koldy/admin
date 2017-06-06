<?php declare(strict_types=1);

namespace KoldyAdmin;

use Koldy\Application;

final class Config
{

    /**
     * @return \Koldy\Config
     */
    public static function getConfig(): \Koldy\Config
    {
        return Application::getConfig('koldy-admin');
    }
}