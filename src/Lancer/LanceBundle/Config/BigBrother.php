<?php

namespace Lancer\LanceBundle\Config;

final class BigBrother
{
    const CONFIG_FOLDER = '/../app/';
    const CONFIG_NAME = 'lanceConfig.xml';
    const INSTALL_NAME = 'install.xml';

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
        $filePath = getcwd() . self::CONFIG_FOLDER . self::CONFIG_NAME;

        if (!file_exists($filePath)) {
            throw new \Exception("Can't load config file.");
        } else {
            $xml              = file_get_contents($filePath);
            self::$configTree = new \SimpleXMLElement($xml);
        }
    }

    public static function getInstallContent()
    {
        $filePath = getcwd() . self::CONFIG_FOLDER . self::INSTALL_NAME ;

        if (!file_exists($filePath)) {
            throw new \Exception("Can't load install content.");
        } else {
            $xml = file_get_contents($filePath);
            return new \SimpleXMLElement($xml);
        }
    }
}