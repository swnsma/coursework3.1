<?php
namespace PatientDiseaseBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;
use Lancer\LanceBundle\Model\DbConnection;

class PatientDiseaseCollection extends AbstractCollection
{
    public function __construct()
    {
        parent::__construct('patient_disease');
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
        $sql = $this->_getQuery(). " WHERE patient_id = ?";
        if ($active) {
            $sql .= " AND $this->_tableName.healthy = 0";
        }

        $sth = $connection->prepare($sql);
        $sth->execute(array($patientId));

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
        $sql = $this->_getQuery().  " WHERE user_id = ?";
        if ($active) {
            $sql .= " AND $this->_tableName.healthy = 0";
        }
        $sth = $connection->prepare($sql);
        $sth->execute(array($userId));
        $result = $sth->fetchAll();
        if(empty($result[0]['id'])) {
            return array();
        } else {
            return $result;
        }
    }


    private function _getQuery()
    {
        return <<<SQl
        SELECT $this->_tableName.id, $this->_tableName.patient_id, $this->_tableName.user_id,
        $this->_tableName.disease_id, $this->_tableName.illness_start, $this->_tableName.illness_end,
        $this->_tableName.healthy, $this->_tableName.notes, CONCAT(user.name, user.second_name) as user_name,
        CONCAT(patient.name, patient.second_name) as patient_name, disease.title, COUNT( * ) AS total_count
        FROM $this->_tableName
                INNER JOIN user ON user.id = user_id
                INNER JOIN patient ON patient.id = patient_id
                INNER JOIN disease ON disease.id = disease_id
SQl;
    }
}