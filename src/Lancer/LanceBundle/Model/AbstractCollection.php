<?php
namespace Lancer\LanceBundle\Model;

abstract class AbstractCollection
{
    protected $_tableName;
    protected $_conditions = array();

    public function __construct()
    {

    }

    abstract public function getAllItems();

}