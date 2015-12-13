<?php
namespace Lancer\LanceBundle\Model;

abstract class AbstractCollection
{
    protected $_items = array();
    protected $_tableName = null;
    protected $_primary = null;
    protected $_itemsData = array();

    public function __construct($_tableName) {
        $this->_tableName = $_tableName;
    }

    abstract public function getAllItems();

    abstract public function getAllItemsData();

    protected function _load()
    {
        $connection = DbConnection::getInstance()->getConnection();
        $sth = $connection->query("SELECT * FROM $this->_tableName");
        $sth->execute();
        $this->_itemsData = $sth->fetchAll();
    }
}