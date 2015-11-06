<?php
namespace Lancer\LanceBundle\Model;

use Lancer\LanceBundle\Config\BigBrother;

final class DbConnection
{
    private static $_connection;

    public static function getConnection()
    {
        if (is_null(self::$_connection)) {
            $settings          = self::getConfigToConnectionString();
            self::$_connection = new \PDO($settings['string'], $settings['username'], $settings['password']);
        }

        return self::$_connection;
    }

    private function __construct()
    {
    }

    private static function getConfigToConnectionString()
    {
        $config   = BigBrother::getConfig();
        $dbConfig = $config->database;

        $connectionString = (string)$dbConfig->driver
            . ':host=' . (string)$dbConfig->host
            . (!empty($dbConfig->port) ? ';port=' . (string)$dbConfig->port : '')
            . ';dbname='
            . (string)$dbConfig->schema;

        return array(
            'string'   => $connectionString,
            'username' => (string)$dbConfig->username,
            'password' => (string)$dbConfig->password
        );
    }
}