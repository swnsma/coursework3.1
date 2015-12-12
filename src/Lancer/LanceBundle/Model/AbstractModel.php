<?php
namespace Lancer\LanceBundle\Model;

abstract class AbstractModel extends MagicObject
{
    protected $_mainTable;
    protected $_primary;
    private $hasChanges = false;
    private $isDeleted = false;
    protected $_references = array();


    public function __construct($mainTable, $primary)
    {
        $this->_mainTable = $mainTable;
        $this->_primary = $primary;
    }

    final public function load($value, $field = null)
    {
        $this->_beforeLoad();
        $this->_load($value, $field);
        $this->_afterLoad();
    }

    /**
     * Load record from the table.
     *
     * @param      $value
     * @param null $field
     *
     * @return $this
     */
    protected function _load($value, $field = null)
    {
        if (is_null($field)) {
            $field = $this->_primary;
        }
        $STH = DbConnection::getInstance()->getConnection()->prepare("SELECT * FROM $this->_mainTable WHERE $field = :value");
        $STH->execute(array(
            'value' => $value
        ));
        $data = $STH->fetch();
        $this->setOriginData($data);

        return $this;
    }

    protected function _beforeLoad()
    {
        $this->unsData();
    }

    protected function _afterLoad()
    {
        $this->setData($this->getOriginData());
    }

    /**
     * Detect changes in model. Don't save unchanged model.
     */
    protected function _detectChanges()
    {
        $keys = array_keys($this->getData());
        foreach ($keys as $key) {
            if ($this->dataChanged($key)) {
                $this->hasChanges = true;
                break;
            }
        }
    }

    protected function _save()
    {
        if ($this->isDeleted) {
            $this->_executeDeleteProcess();
        } else {
            $this->_executeSaveProcess();
            $this->setOriginData($this->getData());
        }
    }

    protected function _beforeSave()
    {
        $this->_detectChanges();
    }

    final public function save()
    {
        $this->_beforeSave();
        if ($this->hasChanges) {
            $this->_save();
            $this->hasChanges = false;
        }
        $this->_afterSave();

        return $this;
    }

    protected function _afterSave()
    {

    }

    /**
     * Set delete flag.
     */
    public function delete()
    {
        $this->isDeleted = true;
        $this->hasChanges = true;
    }

    /**
     * Delete record from the table.
     */
    protected function _executeDeleteProcess()
    {
        $STH = DbConnection::getInstance()
            ->getConnection()
            ->prepare("DELETE FROM $this->_mainTable WHERE $this->_primary = :value");
        $STH->execute(array("value" => $this->getId()));
    }

    /**
     * Save data.
     */
    protected function _executeSaveProcess()
    {
        $data = $this->getData();
        $keys = array_keys($data);
        $keysIns = implode(',', $keys);
        $keysUpd = implode('=?,', $keys);
        $count = count($data);
        $str = str_repeat('?', $count);
        $str = str_split($str);
        $str = implode(',', $str);
        $values = array();

        foreach ($data as $value) {
            if (is_object($value)) {
                $values[] = $value->getId();
            } else {
                $values[] = $value;
            }
        }

        $values = array_merge($values, $values);
        $query = "INSERT INTO $this->_mainTable ($keysIns) VALUES ($str) ON DUPLICATE KEY UPDATE $keysUpd=?";
        $STH = DbConnection::getInstance()
            ->getConnection()->prepare($query);
        $STH->execute($values);
        if (!$this->getId()){
        $this->setId(DbConnection::getInstance()->getConnection()->lastInsertId());
        }
    }
}