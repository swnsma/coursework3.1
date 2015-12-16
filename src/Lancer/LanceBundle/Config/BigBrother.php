<?php

namespace Lancer\LanceBundle\Config;

final class BigBrother
{
    const CONFIG_FOLDER = '/../app/';
    const CONFIG_NAME = 'lanceConfig.xml';
    const INSTALL_NAME = 'install.xml';

    /**
     * @var \SimpleXMLElement
     */
    private static $configTree = null;

    public static function getConfig()
    {
        if (is_null(self::$configTree)) {
            self::initConfig();
        }

        return self::$configTree;
    }

    public static function updateConfig($config)
    {
        if($config instanceof \SimpleXMLElement) {
            self::$configTree = $config;
        } elseif(is_array($config)) {
            $xml = new \SimpleXMLElement('<config></config>');
            $xml->addChild('version', '0.0.0');
            $db = $xml->addChild('database');
            $db->addChild('driver', 'mysql');
            $db->addChild('host', $config['server']);
            $db->addChild('port', $config['port']);
            $db->addChild('schema', $config['schema']);
            $db->addChild('username', $config['user']);
            $db->addChild('password', $config['pass']);
            self::$configTree =$xml;
        }
    }

    private static function initConfig()
    {
        $filePath = getcwd() . self::CONFIG_FOLDER . self::CONFIG_NAME;

        if (!file_exists($filePath)) {
            throw new \Exception("Can't load config file. Please, install application first.");
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

    public static function saveConfig()
    {
        $filePath = getcwd() . self::CONFIG_FOLDER . self::CONFIG_NAME;
        if (!empty(self::$configTree)) {
            $dom = new \DOMDocument("1.0");
            $dom->formatOutput = true;
            $dom->loadXML(self::$configTree->asXML());
            file_put_contents($filePath, $dom->saveXML());
        }
    }
}