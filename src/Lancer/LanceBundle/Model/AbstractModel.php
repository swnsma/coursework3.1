<?php
namespace Lancer\LanceBundle\Model;

 class AbstractModel extends MagicObject
{
    protected $mainTable;
    protected $primary;

    public function __construct($mainTable, $primary)
    {
        $this->mainTable = $mainTable;
        $this->primary = $primary;
    }

    final public function load($value, $field = null)
    {
        $statment = DbConnection::getConnection()->prepare("SELECT * FROM $this->mainTable WHERE $this->primary = $field");
        $value  = $statment->fetch();
        return $value;
    }
}