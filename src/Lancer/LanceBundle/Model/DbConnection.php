<?php
namespace Lancer\LanceBundle\Model;

use Lancer\LanceBundle\Config\BigBrother;
use Symfony\Component\Config\Definition\Exception\Exception;

final class DbConnection
{
    private static $_connection = null;
    private static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new DbConnection();
        }

        return self::$_instance;
    }

    private function __construct()
    {
        try {
            $settings          = self::getConfigToConnectionString();
            self::$_connection = new \PDO($settings['string'], $settings['username'], $settings['password']);
            self::$_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$_connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Connection to database failed.');
        }
    }

    public function getConnection() {
        return self::$_connection;
    }

    private function getConfigToConnectionString()
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