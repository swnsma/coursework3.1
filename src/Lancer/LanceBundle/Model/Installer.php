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
    }

    protected function _install($installNode)
    {
        if (version_compare($installNode->version, $this->version) >= 0 ) {
            foreach ($installNode->content as $task) {
                $query = $this->_formatTask($task);
                DbConnection::getInstance()->getConnection()->query($query);
            }
        }
    }

    protected function _formatTask($task)
    {
        $sql = '';
        if (!empty($task->create)) {
            $sql .= 'CREATE ' .
                $task->exist ? ' IF NOT EXISTS' : '' .
                strtoupper($task->create) . ' ' . $task->name .'(';
            foreach ($task->fields as $field) {
                $rows[] = $this->_formatRow($field);
            }
            $sql .= implode(', ', $rows);
            $sql .= ');';

        } elseif($task->drop) {
            $sql .= 'DROP ' .
                $task->exist ? ' IF EXISTS ': '' .
                strtoupper($task->drop) . ' ' . $task->name . ';';
        } elseif($task->alter) {
            $sql .= 'ALTER TABLE ' . strtoupper($task->name) . ' ' . $this->_alter($task->alter, $task->action);
        }

        return $sql;
    }

    protected function _alter($type, $action)
    {
        switch ($type) {
            case "add":
        }
    }

    protected function _formatRow($field)
    {
        return $str = $field->title . ' ' .
        $field->type .
        $field->size ? '(' . $field->size . ')' : '' .
        $field->a_i ? ' AUTO_INCREMENT ' : '' .
        $field->primary ? ' PRIMARY KEY ' : '' .
        $field->nulls ? ' NULL ' : ' NOT NULL ' .
        $field->default ? ' DEFAULT ' . $field->default . ' ' : '';
    }

}