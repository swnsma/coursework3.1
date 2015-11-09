<?php

namespace Lancer\LanceBundle\Config;

final class BigBrother
{
    const CONFIG_FOLDER = '/../app/';
    const CONFIG_NAME = 'lanceConfig.xml';
    private static $configTree = null;

    public static function getConfig()
    {
        if (is_null(self::$configTree)) {
            self::initConfig();
        }

        return self::$configTree;
    }

    private static function initConfig()
    {
        if (!file_exists( getcwd() . self::CONFIG_FOLDER . self::CONFIG_NAME)) {
            throw new \Exception("Can't load config file.");
        } else {
            $xml              = file_get_contents(getcwd() . self::CONFIG_FOLDER . self::CONFIG_NAME);
            self::$configTree = new \SimpleXMLElement($xml);
        }
    }
}