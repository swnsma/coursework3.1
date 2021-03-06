<?php
namespace PatientServiceBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;
use Lancer\LanceBundle\Model\DbConnection;

class PatientServiceCollection extends  AbstractCollection
{
    public function __construct()
    {
        parent::__construct('patient_service');
    }

    public function getAllItems()
    {
        if (empty($this->_items)) {
            $data = $this->getAllItemsData();
            foreach ($data as $item) {
                $this->_items[] = new PatientDisease($item);
            }
        }

        return $this->_items;
    }

    public function getAllItemsData()
    {
        if (empty($this->_itemsData)) {
            $this->_load();
        }

        return $this->_itemsData;
    }

    public function getUserItemsData($userId, $active=false)
    {
        if (intval($userId)) {
            return $this->_userSpecifiedLoad($userId, $active);
        } else {
            return $this->getAllItemsData();
        }
    }

    public function getPatientItemsData($patientId, $active = false)
    {
        if (intval($patientId)) {
            return $this->_patientSpecifiedLoad($patientId, $active);
        } else {
            return $this->getAllItemsData();
        }
    }

    protected function _patientSpecifiedLoad($patientId, $active = false)
    {
        $connection = DbConnection::getInstance()->getConnection();
        $sth = $connection->prepare("CALL GetPatientSpecifiedPatientService(?, ?)");
        $sth->execute(array($patientId, $active?1:0));
        $result = $sth->fetchAll();
        if(empty($result[0]['id'])) {
            return array();
        } else {
            return $result;
        }
    }


    protected function _userSpecifiedLoad($userId, $active = false)
    {
        $connection = DbConnection::getInstance()->getConnection();
        $sth = $connection->prepare("CALL GetUserSpecifiedPatientService(?, ?)");
        $sth->execute(array($userId, $active?1:0));
        $result = $sth->fetchAll();

        if(empty($result[0]['id'])) {
            return array();
        } else {
            return $result;
        }
    }
}