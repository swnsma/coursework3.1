<?php
namespace Lancer\LanceBundle;

use Lancer\LanceBundle\Config\BigBrother;
use Lancer\LanceBundle\Model\DbConnection;

class Installer
{
    private $installContent = null;
    private $config = null;

    public function __construct()
    {
        $this->installContent = BigBrother::getInstallContent();
        $this->config = BigBrother::getConfig();
    }

    public function run()
    {

        foreach ($this->installContent->installs as $install) {
            $this->_install($install);
        }
        BigBrother::saveConfig();
    }

    protected function _install($installNode)
    {
        try {
            DbConnection::getInstance()->getConnection()->beginTransaction();
            if (version_compare($installNode->version, $this->config->version) >= 0) {
                foreach ($installNode->content as $task) {
                    DbConnection::getInstance()->getConnection()->query((string)$task);
                }
            }
            DbConnection::getInstance()->getConnection()->commit();
            $this->config->version = $installNode->verion;
            BigBrother::updateConfig($this->config);

        } catch (\Exception $e) {
            DbConnection::getInstance()->getConnection()->rollBack();
            throw new \Exception("Install problems. Please check install content and repeat.");
        }
    }

}